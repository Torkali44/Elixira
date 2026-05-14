<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::with('vendorProfile.user')
            ->withCount('items')
            ->latest()
            ->paginate(15);
            
        return view('admin.brands.index', compact('brands'));
    }
}
