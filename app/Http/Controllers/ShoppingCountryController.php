<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShoppingCountryController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'country_code' => 'required|in:KSA,UAE',
        ]);

        session(['shopping_country' => $request->country_code]);

        return redirect()->back();
    }
}
