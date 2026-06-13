<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Contracts\View\View;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs.
     */
    public function index(): View
    {
        $faqs = Faq::where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('faqs.index', compact('faqs'));
    }
}
