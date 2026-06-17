<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SpecialItemOffer;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['items' => function ($query) {
            $query->publiclyVisible()->with('category', 'brandModel');
        }])->get();
        $items = Item::with('category', 'brandModel', 'countryPrices')->publiclyVisible()->get();
        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.index', compact('categories', 'items', 'privateOfferQuantities'));
    }

    public function show(Item $item)
    {
        if (! $item->isPubliclyVisible()) {
            abort(404);
        }

        $item->load('category', 'images', 'brandModel', 'ratings.user', 'countryPrices');

        // Find other approved products with the same name sold by different brands
        $otherSellers = Item::with('brandModel')
            ->where('name', $item->name)
            ->where('id', '!=', $item->id)
            ->publiclyVisible()
            ->get();

        $relatedItems = Item::with('category', 'brandModel')
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->publiclyVisible()
            ->whereNotIn('id', $otherSellers->pluck('id'))
            ->take(4)
            ->get();

        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.show', compact('item', 'relatedItems', 'otherSellers', 'privateOfferQuantities'));
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
