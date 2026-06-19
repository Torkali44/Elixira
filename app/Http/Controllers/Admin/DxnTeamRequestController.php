<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DxnTeamRequest;
use App\Models\User;
use App\Support\DxnApplicationService;
use App\Support\UserNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DxnTeamRequestController extends Controller
{
    public function index(): View
    {
        $requests = DxnTeamRequest::query()->with('user')->latest()->paginate(20);

        return view('admin.dxn-team-requests.index', compact('requests'));
    }

    public function show(DxnTeamRequest $dxnTeamRequest): View
    {
        $dxnTeamRequest->load('user');
        $dxnTeamRequest->markAsRead();

        $whatsAppUrl = app(DxnApplicationService::class)->whatsAppUrlForApplication($dxnTeamRequest);
        $tagColors = config('dxn.default_tag_colors', []);

        return view('admin.dxn-team-requests.show', [
            'request' => $dxnTeamRequest,
            'whatsAppUrl' => $whatsAppUrl,
            'tagColors' => $tagColors,
        ]);
    }

    public function update(Request $request, DxnTeamRequest $dxnTeamRequest): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'assigned_dxn_member_code' => 'nullable|string|max:100',
            'dxn_tag_color' => 'nullable|string|max:20',
            'admin_notes' => 'nullable|string|max:5000',
            'dxn_badge_image' => 'nullable|image|max:2048',
        ]);

        $previousStatus = $dxnTeamRequest->status;

        $dxnTeamRequest->update([
            'status' => $request->status,
            'assigned_dxn_member_code' => $request->assigned_dxn_member_code,
            'dxn_tag_color' => $request->dxn_tag_color,
            'admin_notes' => $request->admin_notes,
        ]);

        if ($request->hasFile('dxn_badge_image')) {
            if ($dxnTeamRequest->dxn_badge_image) {
                Storage::disk('public')->delete($dxnTeamRequest->dxn_badge_image);
            }
            $dxnTeamRequest->update([
                'dxn_badge_image' => $request->file('dxn_badge_image')->store('dxn_badges', 'public'),
            ]);
        }

        if ($request->status === 'approved') {
            $memberCode = $request->assigned_dxn_member_code ?: $dxnTeamRequest->member_code;
            if (filled($memberCode)) {
                $request->merge(['assigned_dxn_member_code' => $memberCode]);
                try {
                    $this->assignDxnMembership($request, $dxnTeamRequest);
                } catch (ValidationException $exception) {
                    return redirect()->route('admin.dxn-team-requests.show', $dxnTeamRequest)
                        ->withErrors($exception->errors())
                        ->withInput();
                }
            }
        }

        if ($previousStatus !== $request->status) {
            $this->notifyApplicant($dxnTeamRequest, $request->status);
        }

        return redirect()->route('admin.dxn-team-requests.show', $dxnTeamRequest)
            ->with('success', __('admin.dxn_team_requests.updated'));
    }

    public function destroy(DxnTeamRequest $dxnTeamRequest): RedirectResponse
    {
        $dxnTeamRequest->delete();

        return redirect()->route('admin.dxn-team-requests.index')->with('success', __('admin.dxn_team_requests.deleted'));
    }

    protected function assignDxnMembership(Request $request, DxnTeamRequest $dxnTeamRequest): void
    {
        $user = $dxnTeamRequest->user
            ?? User::query()->where('email', $dxnTeamRequest->email)->first();

        if ($user === null) {
            return;
        }

        $memberCode = strtoupper(trim((string) $request->assigned_dxn_member_code));

        $codeTakenByOther = User::query()
            ->where('user_code', $memberCode)
            ->where('id', '!=', $user->id)
            ->exists();

        if ($codeTakenByOther) {
            throw ValidationException::withMessages([
                'assigned_dxn_member_code' => __('admin.dxn_team_requests.code_already_used', ['code' => $memberCode]),
            ]);
        }

        $badgePath = $user->dxn_badge_image;
        if ($request->hasFile('dxn_badge_image')) {
            if ($badgePath) {
                Storage::disk('public')->delete($badgePath);
            }
            $badgePath = $request->file('dxn_badge_image')->store('dxn_badges', 'public');
        }

        $user->update([
            'is_dxn_verified' => true,
            'dxn_member_code' => $memberCode,
            'user_code' => $memberCode,
            'dxn_tag_color' => $request->dxn_tag_color ?: config('dxn.default_tag_colors.primary'),
            'dxn_badge_image' => $badgePath,
            'dxn_verified_at' => now(),
        ]);

        $dxnTeamRequest->update([
            'assigned_dxn_member_code' => $memberCode,
        ]);
    }

    protected function notifyApplicant(DxnTeamRequest $dxnTeamRequest, string $status): void
    {
        $notifyUserId = $dxnTeamRequest->user_id
            ?? User::query()->where('email', $dxnTeamRequest->email)->value('id');

        if ($notifyUserId) {
            UserNotifier::send(
                $notifyUserId,
                'dxn_team_request_updated',
                [
                    'team_name' => $dxnTeamRequest->name,
                    'status' => $status,
                ],
                route('dxn-distributor.status', ['application' => $dxnTeamRequest->id, 'notify' => 1])
            );
        }
    }
}
