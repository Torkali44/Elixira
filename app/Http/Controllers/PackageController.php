<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Support\ItemPricingService;
use App\Support\PackagePricingService;
use App\Support\TagService;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(ItemPricingService $pricingService): View
    {
        $selectedCountry = $pricingService->resolveCountryCode(request('country'));

        if (request()->has('country')) {
            session(['shopping_country' => $selectedCountry]);
        }

        $packages = Package::query()
            ->with(['countryPrices', 'items'])
            ->active()
            ->whereHas('countryPrices', function ($query) use ($selectedCountry) {
                $query->where('country_code', $selectedCountry);
            })
            ->latest()
            ->get();

        return view('packages.index', compact('packages', 'selectedCountry'));
    }

    public function show(Package $package): View
    {
        abort_unless($package->isPubliclyVisible(), 404);

        $package->load(['countryPrices', 'items.category', 'items.brandModel', 'tags']);
        $selectedCountry = app(PackagePricingService::class)->getPriceBreakdown(
            $package,
            auth()->user(),
            request('country')
        )['country_code'];

        $tagService = app(TagService::class);
        $relatedBlogs = $tagService->relatedBlogsForPackage($package, 4);
        $relatedReviews = $tagService->relatedReviewsForPackage($package, 6);
        $relatedPackages = $tagService->relatedPackages($package, 4);

        return view('packages.show', compact(
            'package',
            'selectedCountry',
            'relatedBlogs',
            'relatedReviews',
            'relatedPackages',
        ));
    }
}
