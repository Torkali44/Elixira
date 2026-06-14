<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreItemRequest;
use App\Http\Requests\Admin\UpdateItemRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Support\ItemPricingService;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $status = request('status');
        $query = Item::with(['category', 'brandModel.vendorProfile.user'])->latest();

        if ($status === 'new') {
            // New = pending items added in the last 24 hours
            $query->where('status', 'pending')
                ->where('created_at', '>=', now()->subHours(24));
        } elseif ($status === 'pending') {
            // Pending = pending items older than 24 hours
            $query->where('status', 'pending')
                ->where('created_at', '<', now()->subHours(24));
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            $query->whereIn('status', ['rejected', 'rejected_with_notes']);
        }

        $items = $query->paginate(20);

        return view('admin.items.index', compact('items'));
    }

    public function show(Item $item)
    {
        $item->load('category', 'brandModel.vendorProfile.user', 'images');

        return view('admin.items.show', compact('item'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('admin.items.create', compact('categories', 'brands'));
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        $data = $this->normalizeBilingualFields($data);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);
        app(ItemPricingService::class)->syncCountryPrices($item, $request->input('country_prices', []));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/gallery', 'public');
                $item->images()->create(['image' => $path]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Product added successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $item->load('countryPrices');

        return view('admin.items.edit', compact('item', 'categories', 'brands'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');
        $data = $this->normalizeBilingualFields($data);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        } else {
            // Keep the old image if no new one is uploaded
            unset($data['image']);
        }

        $item->update($data);
        app(ItemPricingService::class)->syncCountryPrices($item, $request->input('country_prices', []));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/gallery', 'public');
                $item->images()->create(['image' => $path]);
            }
        }

        return redirect()->route('admin.items.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return redirect()->route('admin.items.index')->with('success', 'Product deleted successfully.');
    }

    public function deleteImage(ItemImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return redirect()->back()->with('success', 'Image removed from gallery.');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizeBilingualFields(array $data): array
    {
        $data['name'] = $data['name_en'];
        $data['description'] = $data['description_en'];

        return $data;
    }
}
