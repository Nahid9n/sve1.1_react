<?php

namespace Database\Seeders;

use App\Theme;
use App\ThemeExtraField;
use Illuminate\Database\Seeder;

class ThemeProductExtraFieldSeeder extends Seeder
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
            'App\Product' => [
                [
                    'field_name' => 'is_new_arrival',
                    'field_label' => 'Is New Arrival?',
                    'field_type' => 'checkbox',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'is_hot_sale',
                    'field_label' => 'Is Hot Sale?',
                    'field_type' => 'checkbox',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'is_best_seller',
                    'field_label' => 'Is Best Seller?',
                    'field_type' => 'checkbox',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'is_trending',
                    'field_label' => 'Is Trending?',
                    'field_type' => 'checkbox',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'is_feature',
                    'field_label' => 'Is Featured?',
                    'field_type' => 'checkbox',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'position',
                    'field_label' => 'Position',
                    'field_type' => 'number',
                    'is_required' => false,
                ],
                [
                    'field_name' => 'hover_image',
                    'field_label' => 'Hover Image',
                    'field_type' => 'file',
                    'is_required' => false,
                ],

            ],
        ];

        foreach ($definitions as $model => $fields) {
            foreach ($fields as $field) {
                ThemeExtraField::updateOrCreate([
                    'theme_path' => $theme,        // active theme dynamically
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

        $this->command->info('Theme extra fields seeded for active theme: ' . $theme);

        // ---- Merge new extra fields into all products ----
        $allExtraFields = [];

        foreach ($definitions['App\Product'] as $field) {
            $allExtraFields[$field['field_name']] = null; // default values
        }

        $products = \App\Product::where('theme_id', $activeTheme->id)->get();

        // dd($products);

        foreach ($products as $product) {
            $existing = $product->extra_fields ?? [];

            // Merge: keep existing values, add missing keys
            $merged = array_merge($allExtraFields, $existing);

            $product->extra_fields = $merged;
            $product->save();
        }

        $this->command->info('Product extra_fields JSON updated for all products.');
    }
}
