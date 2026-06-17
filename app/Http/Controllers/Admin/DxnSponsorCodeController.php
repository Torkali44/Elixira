<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DxnSponsorCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DxnSponsorCodeController extends Controller
{
    public function index(): View
    {
        $codes = DxnSponsorCode::query()
            ->orderBy('sort_order')
            ->orderBy('code')
            ->paginate(20);

        return view('admin.dxn-sponsor-codes.index', compact('codes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', 'unique:dxn_sponsor_codes,code'],
            'sponsor_name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]);

        DxnSponsorCode::create([
            'code' => strtoupper(trim($validated['code'])),
            'sponsor_name' => $validated['sponsor_name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.dxn-sponsor-codes.index')
            ->with('success', __('admin.dxn_sponsor_codes.created'));
    }

    public function update(Request $request, DxnSponsorCode $dxnSponsorCode): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100', 'unique:dxn_sponsor_codes,code,'.$dxnSponsorCode->id],
            'sponsor_name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $dxnSponsorCode->update([
            'code' => strtoupper(trim($validated['code'])),
            'sponsor_name' => $validated['sponsor_name'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.dxn-sponsor-codes.index')
            ->with('success', __('admin.dxn_sponsor_codes.updated'));
    }

    public function destroy(DxnSponsorCode $dxnSponsorCode): RedirectResponse
    {
        $dxnSponsorCode->delete();

        return redirect()->route('admin.dxn-sponsor-codes.index')
            ->with('success', __('admin.dxn_sponsor_codes.deleted'));
    }
}
