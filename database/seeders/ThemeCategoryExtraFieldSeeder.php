<?php

namespace Database\Seeders;

use App\Theme;
use App\ThemeExtraField;
use Illuminate\Database\Seeder;

class ThemeCategoryExtraFieldSeeder extends Seeder
{
    public function run(): void
    {
        $activeTheme = Theme::where('is_active', 1)->first();
        if (! $activeTheme) {
            $this->command->info('No active theme found. Seeder skipped.');

            return;
        }
        $theme = $activeTheme->path;
        $definitions = [
            'App\Category' => [
                [
                    'field_name' => 'position',
                    'field_label' => 'Position',
                    'field_type' => 'text',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'is_top_category',
                    'field_label' => 'is_top_category',
                    'field_type' => 'select',
                    'is_required' => false,
                ],
            ],

        ];

        foreach ($definitions as $model => $fields) {
            foreach ($fields as $field) {
                ThemeExtraField::updateOrCreate([
                    'theme_path' => $theme,
                    'model_type' => $model,
                    'field_name' => $field['field_name'],
                ], [
                    'theme_id' => $activeTheme->id,
                    'field_label' => $field['field_label'],
                    'field_type' => $field['field_type'],
                    'is_required' => $field['is_required'],
                    'options' => $field['options'] ?? null,
                ]);
            }
        }

        $this->command->info('Theme extra fields seeded for active theme: '.$theme);

        // ---- Merge new extra fields into all products ----
        $allExtraFields = [];

        foreach ($definitions['App\Category'] as $field) {
            $allExtraFields[$field['field_name']] = null; // default values
        }

        $products = \App\Category::where('theme_id', $activeTheme->id)->get();

        // dd($products);

        foreach ($products as $product) {
            $existing = $product->extra_fields ?? [];

            // Merge: keep existing values, add missing keys
            $merged = array_merge($allExtraFields, $existing);

            $product->extra_fields = $merged;
            $product->save();
        }

        $this->command->info('Category extra_fields JSON updated for all Categories.');
    }
}
