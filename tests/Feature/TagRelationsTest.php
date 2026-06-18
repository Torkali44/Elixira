<?php

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tag;
use App\Models\User;
use App\Models\VendorProfile;
use App\Support\TagService;

test('related products are matched by shared tags instead of random category', function () {
    $categoryA = Category::query()->create(['name' => 'A', 'name_en' => 'A', 'name_ar' => 'أ']);
    $categoryB = Category::query()->create(['name' => 'B', 'name_en' => 'B', 'name_ar' => 'ب']);

    $source = Item::query()->create([
        'category_id' => $categoryA->id,
        'name' => 'Source Product',
        'name_en' => 'Source Product',
        'name_ar' => 'منتج',
        'description' => 'desc',
        'price' => 50,
        'stock' => 5,
        'status' => 'approved',
    ]);

    $taggedMatch = Item::query()->create([
        'category_id' => $categoryB->id,
        'name' => 'Tagged Match',
        'name_en' => 'Tagged Match',
        'name_ar' => 'مطابق',
        'description' => 'desc',
        'price' => 60,
        'stock' => 4,
        'status' => 'approved',
    ]);

    $untaggedSameCategory = Item::query()->create([
        'category_id' => $categoryA->id,
        'name' => 'Untagged Same Category',
        'name_en' => 'Untagged Same Category',
        'name_ar' => 'بدون تاج',
        'description' => 'desc',
        'price' => 70,
        'stock' => 3,
        'status' => 'approved',
    ]);

    $tag = Tag::findOrCreateFromName('wellness');
    $source->tags()->sync([$tag->id]);
    $taggedMatch->tags()->sync([$tag->id]);

    $related = app(TagService::class)->relatedItems($source->fresh(), 4);

    expect($related->pluck('id')->all())->toBe([$taggedMatch->id])
        ->and($related->pluck('id'))->not->toContain($untaggedSameCategory->id);
});

test('related blogs appear for products with shared tags', function () {
    $category = Category::query()->create(['name' => 'Cat', 'name_en' => 'Cat', 'name_ar' => 'قسم']);
    $item = Item::query()->create([
        'category_id' => $category->id,
        'name' => 'Spirulina',
        'name_en' => 'Spirulina',
        'name_ar' => 'سبيرولينا',
        'description' => 'desc',
        'price' => 80,
        'stock' => 5,
        'status' => 'approved',
    ]);

    $relatedBlog = Blog::query()->create([
        'title_en' => 'Spirulina Benefits',
        'title_ar' => 'فوائد السبيرولينا',
        'slug' => 'spirulina-benefits',
        'content_en' => 'Content',
        'content_ar' => 'محتوى',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $otherBlog = Blog::query()->create([
        'title_en' => 'Unrelated Post',
        'title_ar' => 'مقال آخر',
        'slug' => 'unrelated-post',
        'content_en' => 'Content',
        'content_ar' => 'محتوى',
        'is_published' => true,
        'published_at' => now(),
    ]);

    $tag = Tag::findOrCreateFromName('superfoods');
    $item->tags()->sync([$tag->id]);
    $relatedBlog->tags()->sync([$tag->id]);

    $blogs = app(TagService::class)->relatedBlogsForItem($item->fresh(), 4);

    expect($blogs->pluck('id')->all())->toBe([$relatedBlog->id])
        ->and($blogs->pluck('id'))->not->toContain($otherBlog->id);
});

test('similar brands are matched by shared tags', function () {
    $vendor = User::factory()->create(['role' => 'vendor']);
    $profile = VendorProfile::query()->create([
        'user_id' => $vendor->id,
        'brand_name' => 'Main Brand',
        'status' => 'approved',
    ]);

    $brand = Brand::query()->create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Main Brand',
        'is_active' => true,
    ]);

    $similar = Brand::query()->create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Similar Brand',
        'is_active' => true,
    ]);

    $different = Brand::query()->create([
        'vendor_profile_id' => $profile->id,
        'name' => 'Different Brand',
        'is_active' => true,
    ]);

    $tag = Tag::findOrCreateFromName('organic');
    $brand->tags()->sync([$tag->id]);
    $similar->tags()->sync([$tag->id]);

    $results = app(TagService::class)->relatedBrands($brand->fresh(), 4);

    expect($results->pluck('id')->all())->toBe([$similar->id])
        ->and($results->pluck('id'))->not->toContain($different->id);
});
