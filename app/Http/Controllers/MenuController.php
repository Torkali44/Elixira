<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with(['items' => function($query) {
            $query->with('category');
        }])->get();
        $items = Item::with('category')->get();
        return view('menu.index', compact('categories', 'items'));
    }

    public function show(Item $item)
    {
        $item->load('category', 'images');
        $relatedItems = Item::with('category')
            ->where('category_id', $item->category_id)
            ->where('id', '!=', $item->id)
            ->take(4)
            ->get();
            
        return view('menu.show', compact('item', 'relatedItems'));
    }
}
