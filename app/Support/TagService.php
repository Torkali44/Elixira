<?php

namespace App\Support;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Item;
use App\Models\Package;
use App\Models\Review;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TagService
{
    /**
     * @return list<string>
     */
    public function parseInput(?string $input): array
    {
        if ($input === null || trim($input) === '') {
            return [];
        }

        $parts = preg_split('/[,;]+/', $input) ?: [];

        return collect($parts)
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->unique(fn (string $tag) => Str::lower($tag))
            ->values()
            ->all();
    }

    public function syncFromInput(Model $model, ?string $input): void
    {
        if (! method_exists($model, 'tags')) {
            return;
        }

        $tagIds = collect($this->parseInput($input))
            ->map(fn (string $name) => Tag::findOrCreateFromName($name)->id)
            ->all();

        $model->tags()->sync($tagIds);
    }

    /**
     * @return Collection<int, Item>
     */
    public function relatedItems(Item $item, int $limit = 4): Collection
    {
        $tagIds = $item->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return Item::query()
            ->with(['category', 'brandModel'])
            ->publiclyVisible()
            ->where('id', '!=', $item->id)
            ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $tagIds))
            ->withCount(['tags as shared_tags_count' => fn ($query) => $query->whereIn('tags.id', $tagIds)])
            ->orderByDesc('shared_tags_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Brand>
     */
    public function relatedBrands(Brand $brand, int $limit = 4): Collection
    {
        $tagIds = $brand->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return Brand::query()
            ->where('is_active', true)
            ->where('id', '!=', $brand->id)
            ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $tagIds))
            ->withCount(['tags as shared_tags_count' => fn ($query) => $query->whereIn('tags.id', $tagIds)])
            ->orderByDesc('shared_tags_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Blog>
     */
    public function relatedBlogsForItem(Item $item, int $limit = 4): Collection
    {
        $tagIds = $item->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return $this->publishedBlogsWithSharedTags($tagIds, null, $limit);
    }

    /**
     * @return Collection<int, Blog>
     */
    public function relatedBlogs(Blog $blog, int $limit = 4): Collection
    {
        $tagIds = $blog->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return $this->publishedBlogsWithSharedTags($tagIds, $blog->id, $limit);
    }

    /**
     * @return Collection<int, Review>
     */
    public function relatedReviewsForItem(Item $item, int $limit = 6): Collection
    {
        $tagIds = $item->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return $this->approvedReviewsWithSharedTags($tagIds, $limit);
    }

    /**
     * @return Collection<int, Blog>
     */
    public function relatedBlogsForPackage(Package $package, int $limit = 4): Collection
    {
        $tagIds = $package->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return $this->publishedBlogsWithSharedTags($tagIds, null, $limit);
    }

    /**
     * @return Collection<int, Review>
     */
    public function relatedReviewsForPackage(Package $package, int $limit = 6): Collection
    {
        $tagIds = $package->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return $this->approvedReviewsWithSharedTags($tagIds, $limit);
    }

    /**
     * @return Collection<int, Package>
     */
    public function relatedPackages(Package $package, int $limit = 4): Collection
    {
        $tagIds = $package->tags()->pluck('tags.id');

        if ($tagIds->isEmpty()) {
            return new Collection;
        }

        return Package::query()
            ->with(['countryPrices', 'items'])
            ->active()
            ->where('id', '!=', $package->id)
            ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $tagIds))
            ->withCount(['tags as shared_tags_count' => fn ($query) => $query->whereIn('tags.id', $tagIds)])
            ->orderByDesc('shared_tags_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>|array<int, int>  $tagIds
     * @return Collection<int, Review>
     */
    private function approvedReviewsWithSharedTags($tagIds, int $limit): Collection
    {
        return Review::query()
            ->where('status', 'approved')
            ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $tagIds))
            ->withCount(['tags as shared_tags_count' => fn ($query) => $query->whereIn('tags.id', $tagIds)])
            ->orderByDesc('shared_tags_count')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @param  \Illuminate\Support\Collection<int, int>|array<int, int>  $tagIds
     * @return Collection<int, Blog>
     */
    private function publishedBlogsWithSharedTags($tagIds, ?int $excludeBlogId, int $limit): Collection
    {
        return Blog::query()
            ->where('is_published', true)
            ->when($excludeBlogId, fn ($query) => $query->where('id', '!=', $excludeBlogId))
            ->whereHas('tags', fn ($query) => $query->whereIn('tags.id', $tagIds))
            ->withCount(['tags as shared_tags_count' => fn ($query) => $query->whereIn('tags.id', $tagIds)])
            ->orderByDesc('shared_tags_count')
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }
}
