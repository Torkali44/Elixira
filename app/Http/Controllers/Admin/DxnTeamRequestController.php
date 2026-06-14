<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DxnTeamRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        return view('admin.dxn-team-requests.show', ['request' => $dxnTeamRequest]);
    }

    public function update(Request $request, DxnTeamRequest $dxnTeamRequest): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $dxnTeamRequest->update(['status' => $request->status]);

        return redirect()->route('admin.dxn-team-requests.show', $dxnTeamRequest)
            ->with('success', __('admin.dxn_team_requests.updated'));
    }

    public function destroy(DxnTeamRequest $dxnTeamRequest): RedirectResponse
    {
        $dxnTeamRequest->delete();

        return redirect()->route('admin.dxn-team-requests.index')->with('success', __('admin.dxn_team_requests.deleted'));
    }
}
