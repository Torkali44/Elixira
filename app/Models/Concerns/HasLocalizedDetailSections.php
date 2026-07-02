<?php

namespace App\Models\Concerns;

trait HasLocalizedDetailSections
{
    public function getLocalBenefitsAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->benefits_ar ?: $this->benefits_en;
        }

        return $this->benefits_en ?: $this->benefits_ar;
    }

    public function getLocalIngredientsAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->ingredients_ar ?: $this->ingredients_en;
        }

        return $this->ingredients_en ?: $this->ingredients_ar;
    }

    public function getLocalUsageInstructionsAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->usage_instructions_ar ?: $this->usage_instructions_en;
        }

        return $this->usage_instructions_en ?: $this->usage_instructions_ar;
    }

    public function getLocalWarningsAttribute(): ?string
    {
        if (app()->getLocale() === 'ar') {
            return $this->warnings_ar ?: $this->warnings_en;
        }

        return $this->warnings_en ?: $this->warnings_ar;
    }

    /**
     * @return list<array{id: string, title: string, content: string}>
     */
    public function localizedDetailSections(): array
    {
        $sections = [];

        $description = $this->local_long_description ?: $this->local_description;
        if (filled($description)) {
            $sections[] = [
                'id' => 'description',
                'title' => __('shop.section_description'),
                'content' => $description,
            ];
        }

        $extraSections = [
            'benefits' => 'shop.section_benefits',
            'ingredients' => 'shop.section_ingredients',
            'usage_instructions' => 'shop.section_usage',
            'warnings' => 'shop.section_warnings',
        ];

        foreach ($extraSections as $field => $labelKey) {
            $accessor = match ($field) {
                'benefits' => 'local_benefits',
                'ingredients' => 'local_ingredients',
                'usage_instructions' => 'local_usage_instructions',
                'warnings' => 'local_warnings',
            };

            $content = $this->{$accessor};
            if (filled($content)) {
                $sections[] = [
                    'id' => $field,
                    'title' => __($labelKey),
                    'content' => $content,
                ];
            }
        }

        return $sections;
    }
}
