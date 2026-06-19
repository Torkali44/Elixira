<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Support\PackagePricingService;
use App\Support\TagService;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::query()
            ->with(['countryPrices', 'items'])
            ->active()
            ->latest()
            ->get();

        return view('packages.index', compact('packages'));
    }

    public function show(Package $package): View
    {
        abort_unless($package->is_active, 404);

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
