<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomePageSection;
use App\Models\Item;
use App\Models\SpecialItemOffer;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sections = HomePageSection::query()->active()->ordered()->get();

        $featuredItems = Item::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        if ($featuredItems->isEmpty()) {
            $featuredItems = Item::with('category')
                ->latest()
                ->take(8)
                ->get();
        }

        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('home', compact('sections', 'featuredItems', 'privateOfferQuantities'));
    }

    public function explore(): View
    {
        $categories = Category::withCount('items')->orderBy('name')->get();
        $featuredItems = Item::with('category')
            ->where('is_featured', true)
            ->latest()
            ->take(8)
            ->get();

        $privateOfferQuantities = $this->privateOfferQuantitiesForCurrentUser();

        return view('explore', compact('categories', 'featuredItems', 'privateOfferQuantities'));
    }

    public function about(): View
    {
        return view('about');
    }

    public function contact(): View
    {
        return view('contact');
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
