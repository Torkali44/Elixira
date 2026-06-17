<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDxnDistributorApplicationRequest;
use App\Http\Requests\StoreDxnExistingMemberRequest;
use App\Models\DxnSponsorCode;
use App\Models\DxnTeamRequest;
use App\Models\User;
use App\Support\DxnApplicationService;
use App\Support\UserNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DxnTeamRequestController extends Controller
{
    public function create(): View
    {
        $user = auth()->user();

        return view('dxn-team.create', [
            'user' => $user,
            'sponsorCodes' => DxnSponsorCode::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('code')
                ->get(),
            'whatsAppNumber' => app(DxnApplicationService::class)->adminWhatsAppNumber(),
        ]);
    }

    public function store(StoreDxnDistributorApplicationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $hasHeir = $request->boolean('has_heir');

        $application = DxnTeamRequest::create([
            'user_id' => $user?->id,
            'application_type' => 'new_distributor',
            'name' => $request->validated('name'),
            'gender' => $request->validated('gender'),
            'date_of_birth' => $request->validated('date_of_birth'),
            'id_number' => $request->validated('id_number'),
            'passport_number' => $request->validated('passport_number'),
            'nationality' => $request->validated('nationality'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'sponsor_code' => $request->validated('sponsor_code'),
            'sponsor_name' => $request->validated('sponsor_name'),
            'member_code' => $request->validated('sponsor_code'),
            'country' => $request->validated('country'),
            'has_heir' => $hasHeir,
            'heir_name' => $hasHeir ? $request->validated('heir_name') : null,
            'heir_relationship' => $hasHeir ? $request->validated('heir_relationship') : null,
            'heir_id_number' => $hasHeir ? $request->validated('heir_id_number') : null,
            'heir_passport_number' => $hasHeir ? $request->validated('heir_passport_number') : null,
            'address' => $request->validated('address'),
            'address_country' => $request->validated('address_country'),
            'address_city' => $request->validated('address_city'),
            'postal_code' => $request->validated('postal_code'),
            'contract_accepted_at' => now(),
            'status' => 'pending',
        ]);

        $this->notifyAdmins($application);

        return $this->redirectAfterSubmit($user, $application, __('dxn_team.form_success'));
    }

    public function storeExistingMember(StoreDxnExistingMemberRequest $request): RedirectResponse
    {
        $user = $request->user();

        $application = DxnTeamRequest::create([
            'user_id' => $user?->id,
            'application_type' => 'existing_member',
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'member_code' => $request->validated('member_code'),
            'message' => $request->validated('message'),
            'status' => 'pending',
        ]);

        $this->notifyAdmins($application);

        return $this->redirectAfterSubmit($user, $application, __('dxn_team.existing_member_success'));
    }

    public function status(Request $request, DxnTeamRequest $application): View|RedirectResponse
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $ownsApplication = $application->user_id === $user->id
            || ($application->user_id === null && strcasecmp($application->email, $user->email) === 0);

        if (! $ownsApplication) {
            abort(403);
        }

        $dxnService = app(DxnApplicationService::class);

        return view('dxn-team.status', [
            'application' => $application,
            'showStatusPopup' => $request->boolean('notify'),
            'whatsAppUrl' => $dxnService->whatsAppUrlForApplication($application),
        ]);
    }

    protected function notifyAdmins(DxnTeamRequest $application): void
    {
        $admins = User::query()->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotifier::send($admin->id, 'dxn_team_request_received', [
                'name' => $application->name,
            ], route('admin.dxn-team-requests.show', $application));
        }
    }

    protected function redirectAfterSubmit(?User $user, DxnTeamRequest $application, string $message): RedirectResponse
    {
        $whatsAppUrl = app(DxnApplicationService::class)->whatsAppUrlForApplication($application);

        if ($user) {
            return redirect()->route('dxn-distributor.status', $application)
                ->with('success', $message)
                ->with('whatsapp_url', $whatsAppUrl);
        }

        return redirect()->route('dxn-distributor.create')
            ->with('success', $message)
            ->with('whatsapp_url', $whatsAppUrl);
    }
}
