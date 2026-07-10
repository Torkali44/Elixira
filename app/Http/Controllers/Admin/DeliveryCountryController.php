<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryCity;
use App\Models\DeliveryCountry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DeliveryCountryController extends Controller
{
    public function index(): View
    {
        $countries = DeliveryCountry::withCount('cities')
            ->orderBy('sort_order')
            ->orderBy('name_en')
            ->paginate(15);

        return view('admin.delivery-countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.delivery-countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCountry($request);
        DeliveryCountry::create($validated);

        return redirect()
            ->route('admin.delivery-countries.index')
            ->with('success', __('admin.delivery_zones.created'));
    }

    public function edit(DeliveryCountry $deliveryCountry): View
    {
        $deliveryCountry->load('cities');

        return view('admin.delivery-countries.edit', compact('deliveryCountry'));
    }

    public function update(Request $request, DeliveryCountry $deliveryCountry): RedirectResponse
    {
        $validated = $this->validateCountry($request, $deliveryCountry);
        $deliveryCountry->update($validated);

        return redirect()
            ->route('admin.delivery-countries.edit', $deliveryCountry)
            ->with('success', __('admin.delivery_zones.updated'));
    }

    public function destroy(DeliveryCountry $deliveryCountry): RedirectResponse
    {
        $deliveryCountry->delete();

        return redirect()
            ->route('admin.delivery-countries.index')
            ->with('success', __('admin.delivery_zones.deleted'));
    }

    public function storeCity(Request $request, DeliveryCountry $deliveryCountry): RedirectResponse
    {
        $validated = $this->validateCity($request);
        $deliveryCountry->cities()->create($validated);

        return redirect()
            ->route('admin.delivery-countries.edit', $deliveryCountry)
            ->with('success', __('admin.delivery_zones.city_created'));
    }

    public function updateCity(Request $request, DeliveryCountry $deliveryCountry, DeliveryCity $deliveryCity): RedirectResponse
    {
        abort_unless($deliveryCity->delivery_country_id === $deliveryCountry->id, 404);

        $validated = $this->validateCity($request);
        $deliveryCity->update($validated);

        return redirect()
            ->route('admin.delivery-countries.edit', $deliveryCountry)
            ->with('success', __('admin.delivery_zones.city_updated'));
    }

    public function destroyCity(DeliveryCountry $deliveryCountry, DeliveryCity $deliveryCity): RedirectResponse
    {
        abort_unless($deliveryCity->delivery_country_id === $deliveryCountry->id, 404);

        $deliveryCity->delete();

        return redirect()
            ->route('admin.delivery-countries.edit', $deliveryCountry)
            ->with('success', __('admin.delivery_zones.city_deleted'));
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCountry(Request $request, ?DeliveryCountry $country = null): array
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('delivery_countries', 'code')->ignore($country?->id),
            ],
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'currency_label_en' => ['required', 'string', 'max:20'],
            'currency_label_ar' => ['required', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['currency_code'] = $country?->currency_code
            ?: strtoupper(preg_replace('/[^A-Za-z]/', '', $validated['currency_label_en']) ?: 'SAR');

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCity(Request $request): array
    {
        $validated = $request->validate([
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);

        return $validated;
    }
}
