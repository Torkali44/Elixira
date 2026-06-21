<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SpecialItemOffer;
use App\Support\ItemPricingService;
use App\Support\TagService;

class MenuController extends Controller
{
    public function index(ItemPricingService $pricingService)
    {
        $selectedCountry = $pricingService->resolveCountryCode(request('country'));

        if (request()->has('country')) {
            session(['shopping_country' => $selectedCountry]);
        }

        $categories = Category::query()
            ->whereHas('items', function ($query) use ($selectedCountry) {
                $query->publiclyVisible()
                    ->whereHas('countryPrices', function ($countryQuery) use ($selectedCountry) {
                        $countryQuery->where('country_code', $selectedCountry);
                    });
            })
            ->orderBy('name')
            ->get();

        $items = Item::query()
            ->with(['category', 'brandModel', 'countryPrices'])
            ->publiclyVisible()
            ->whereHas('countryPrices', function ($query) use ($selectedCountry) {
                $query->where('country_code', $selectedCountry);
            })
            ->orderBy('name')
            ->get();
        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.index', compact('categories', 'items', 'privateOfferQuantities', 'selectedCountry'));
    }

    public function show(Item $item)
    {
        if (! $item->isPubliclyVisible()) {
            abort(404);
        }

        $item->load('category', 'images', 'brandModel', 'ratings.user', 'countryPrices', 'tags');

        $pricingService = app(ItemPricingService::class);
        $selectedCountry = $pricingService->resolveCountryCode(request('country'));

        // Find other approved products with the same name sold by different brands
        $otherSellers = Item::with('brandModel')
            ->where('name', $item->name)
            ->where('id', '!=', $item->id)
            ->publiclyVisible()
            ->get();

        $tagService = app(TagService::class);
        $relatedItems = $tagService->relatedItems($item, 4);
        $relatedBlogs = $tagService->relatedBlogsForItem($item, 4);
        $relatedReviews = $tagService->relatedReviewsForItem($item, 6);

        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.show', compact(
            'item',
            'relatedItems',
            'relatedBlogs',
            'relatedReviews',
            'otherSellers',
            'privateOfferQuantities',
            'selectedCountry'
        ));
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
