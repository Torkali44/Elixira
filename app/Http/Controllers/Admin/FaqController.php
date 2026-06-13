<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.faqs.index', compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data['is_published'] = $request->has('is_published');

        Faq::create($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $data = $request->validate([
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en' => 'required|string',
            'answer_ar' => 'required|string',
            'sort_order' => 'required|integer|min:0',
        ]);

        $data['is_published'] = $request->has('is_published');

        $faq->update($data);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
}
