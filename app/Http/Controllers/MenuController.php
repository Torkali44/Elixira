<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\SpecialItemOffer;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['items' => function($query) {
            $query->with('category');
        }])->get();
        $items = Item::with('category')->get();
        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.index', compact('categories', 'items', 'privateOfferQuantities'));
    }

    public function show(Item $item)
    {
        $item->load('category', 'images');
        $relatedItems = Item::with('category')
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(4)
            ->get();
            
        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('menu.show', compact('item', 'relatedItems', 'privateOfferQuantities'));
    }

    private function privateOfferQuantitiesForCurrentUser(): array
    {
        $user = auth()->user();

        if (!$user) {
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
