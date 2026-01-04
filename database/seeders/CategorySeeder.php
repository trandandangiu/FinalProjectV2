<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     $categories = [
    ['name'=>'Quần Tập Gym', 'slug' => 'quan-tap-gym', 'description'=>'Các loại quần tập gym, quần short, quần dài thể thao', 'image'=>'uploads/category/'],
    ['name'=>'Dụng Cụ Tập', 'slug' => 'dung-cu-tap', 'description'=>'Đai lưng, bao tay, băng bảo vệ và dụng cụ tập luyện', 'image'=>'uploads/category/'],
    ['name'=>'Thực Phẩm Bổ Sung', 'slug' => 'thuc-pham-bo-sung', 'description'=>'Whey protein, BCAA, Creatine, Pre-workout', 'image'=>'uploads/category/'],
    ['name'=>'Áo Tập Gym', 'slug' => 'ao-tap-gym', 'description'=>'Áo thun, tank top, áo tập thể thao', 'image'=>'uploads/category/'],
    ['name'=>'Phụ Kiện Thể Thao', 'slug' => 'phu-kien-the-thao', 'description'=>'Bình lắc, túi tập, khăn tập gym', 'image'=>'uploads/category/'],
];
    foreach($categories as $category)
    {
        Category::create($category);
    }
    }
}
