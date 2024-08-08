<?php

namespace Database\Seeders;

use App\Models\Blog\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

        ];
        collect($categories)->each(fn($category) => BlogCategory::create([
            'name' => $category
        ]));
    }
}
