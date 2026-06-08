<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    private function getBrandId()
    {
        $vendorProfile = auth()->user()->vendorProfile;
        if (! $vendorProfile || ! $vendorProfile->brand) {
            abort(403, 'Brand not found.');
        }

        return $vendorProfile->brand->id;
    }

    public function index()
    {
        $vendorBrandId = $this->getBrandId();
        $items = Item::with('category')->where('brand_id', $vendorBrandId)->latest()->get();

        return view('vendor.items.index', compact('items', 'vendorBrandId'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('vendor.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|max:2048',
            'images.*' => 'image|max:2048',
        ]);

        $data['brand_id'] = $this->getBrandId();
        $data['status'] = 'pending';
        $data['is_featured'] = false; // Vendors can't feature products

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/gallery', 'public');
                $item->images()->create(['image' => $path]);
            }
        }

        return redirect()->route('vendor.items.index')->with('success', 'Product added successfully and is pending approval.');
    }

    public function edit(Item $item)
    {
        if ($item->brand_id !== $this->getBrandId()) {
            abort(403);
        }

        $categories = Category::all();

        return view('vendor.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->brand_id !== $this->getBrandId()) {
            abort(403);
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $resubmitted = $item->status === 'rejected_with_notes';

        if (in_array($item->status, ['rejected_with_notes', 'rejected'], true)) {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        $item->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('items/gallery', 'public');
                $item->images()->create(['image' => $path]);
            }
        }

        $message = $resubmitted
            ? 'Product updated and resubmitted for approval.'
            : 'Product updated successfully.';

        return redirect()->route('vendor.items.index')->with('success', $message);
    }

    public function destroy(Item $item)
    {
        if ($item->brand_id !== $this->getBrandId()) {
            abort(403);
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return redirect()->route('vendor.items.index')->with('success', 'Product deleted successfully.');
    }
}
