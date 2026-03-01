<?php

namespace Database\Factories;

use App\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {

        return [
            'sku' => 'SKUTH2-' . strtoupper(Str::random(8)),
            'thumb' => null,
            'image' => null,
            'gallery_images' => null,
            'name' => $this->faker->words(10, true),
            'slug' => Str::slug($this->faker->unique()->sentence(3)),
            'stock' => rand(10, 200),
            'description' => $this->faker->paragraph,
            'purchase_price' => rand(500, 1500),
            'regular_price' => rand(1600, 2500),
            'sale_price' => rand(1200, 2000),
            'status' => 1,
            'has_variant' => 0,
            // 'free_shipping' => rand(0, 1),
            'is_combo' => 0,
            'is_package' => 0,
            'package_qty' => 1,
            'extra_fields' => [],
            'theme_id' => 13,
            'related_products' => null,
        ];
    }
}
