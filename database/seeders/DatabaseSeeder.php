<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cat = Category::create(['name' => 'Trà sữa']);
        $product = Product::create([
            'title' => 'BÁNH SÔ-CÔ-LA',
            'number' => 20,
            'thumbnail' => 'SOCOLAHL.png',
            'content' => 'Thức uống chinh phục những thực khách khó tính!',
            'id_category' => $cat->id
        ]);
        ProductSize::create([
            'product_id' => $product->id,
            'size' => 'M',
            'price' => 25000
        ]);
    }
}
