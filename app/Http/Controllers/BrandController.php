<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\SpecialItemOffer;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::where('is_active', true)->withCount('items');

        if ($request->filled('country')) {
            $country = $request->country;
            $query->whereJsonContains('service_countries', $country);
        }

        $brands = $query->latest()->paginate(12);

        return view('brands.index', compact('brands'));
    }

    public function show(Brand $brand)
    {
        if (! $brand->is_active) {
            $user = auth()->user();
            $isOwner = $user
                && $brand->vendorProfile
                && (int) $brand->vendorProfile->user_id === (int) $user->id;
            $isAdmin = $user && $user->role === 'admin';

            if ($isOwner || $isAdmin) {
                return view('brands.inactive', compact('brand', 'isOwner', 'isAdmin'));
            }

            abort(404);
        }

        $brand->load(['vendorProfile.user', 'items.category', 'ratings.user']);

        $products = $brand->items()->publiclyVisible()->with('category')->paginate(12);

        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        // Similar brands (same service countries, exclude current)
        $similarBrands = Brand::where('is_active', true)
            ->where('id', '!=', $brand->id)
            ->withCount('items')
            ->take(4)
            ->get();

        return view('brands.show', compact('brand', 'products', 'similarBrands', 'privateOfferQuantities'));
    }

    private function privateOfferQuantitiesForCurrentUser(): array
    {
        $user = auth()->user();

        if (! $user) {
            return [];
        }

        $phoneDigits = preg_replace('/\D+/', '', (string) $user->phone) ?? '';
        $email = strtolower((string) $user->email);

        return SpecialItemOffer::query()
            ->where('is_active', true)
            ->whereColumn('used_quantity', '<', 'quantity')
            ->where(function ($query) use ($user, $phoneDigits, $email) {
                $query->where('user_id', $user->id);
                if ($phoneDigits !== '') {
                    $query->orWhere('target_phone', $phoneDigits);
                }
                if ($email !== '') {
                    $query->orWhereRaw('LOWER(target_email) = ?', [$email]);
                }
            })
            ->get()
            ->groupBy('item_id')
            ->map(fn ($offers) => $offers->sum(fn (SpecialItemOffer $offer) => $offer->remainingQuantity()))
            ->all();
    }
}
