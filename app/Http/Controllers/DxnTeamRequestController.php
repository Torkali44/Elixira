<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDxnTeamRequestRequest;
use App\Models\DxnTeamRequest;
use App\Models\User;
use App\Support\UserNotifier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DxnTeamRequestController extends Controller
{
    public function create(): View
    {
        $user = auth()->user();

        return view('dxn-team.create', [
            'user' => $user,
        ]);
    }

    public function store(StoreDxnTeamRequestRequest $request): RedirectResponse
    {
        $user = $request->user();

        $teamRequest = DxnTeamRequest::create([
            'user_id' => $user?->id,
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'member_code' => $request->validated('member_code'),
            'country' => $request->validated('country'),
            'team_goal' => $request->validated('team_goal'),
            'message' => $request->validated('message'),
            'status' => 'pending',
        ]);

        $admins = User::query()->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            UserNotifier::send($admin->id, 'dxn_team_request_received', [
                'name' => $teamRequest->name,
            ], route('admin.dxn-team-requests.show', $teamRequest));
        }

        return redirect()->route('dxn-team.create')->with('success', __('dxn_team.form_success'));
    }
}
