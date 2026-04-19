<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreItemRequest;
use App\Http\Requests\Admin\UpdateItemRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->latest()->get();
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.items.create', compact('categories'));
    }

    public function store(StoreItemRequest $request)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');

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

        return redirect()->route('admin.items.index')->with('success', 'Product added successfully.');
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(UpdateItemRequest $request, Item $item)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->has('is_featured');

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
}
