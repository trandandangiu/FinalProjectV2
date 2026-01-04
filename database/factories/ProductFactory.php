<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        // Danh sách sản phẩm real cho shop fitness
        $name = $this->faker->randomElement([
            // Quần
            'Quần Short Tập Gym',
            'Quần Dài Thể Thao',
            'Quần Legging Nữ',
            'Quần Jogger',
            
            // Áo
            'Áo Thun Thể Thao',
            'Tank Top Tập Gym',
            'Áo Ba Lỗ',
            'Áo Hoodie',
            
            // Whey Protein
            'Whey Protein Isolate',
            'Whey Protein Concentrate',
            'Mass Gainer',
            'Whey Thực Vật',
            
            // Thực phẩm bổ sung
            'BCAA 2:1:1',
            'Creatine Monohydrate',
            'Pre-workout',
            'Glutamine',
            
            // Dụng cụ
            'Đai Lưng Tập Tạ',
            'Bao Tay Tập Gym',
            'Băng Đầu Gối',
            'Dây Đeo Tạ',
            
            // Phụ kiện
            'Bình Lắc Protein',
            'Túi Tập Gym',
            'Khăn Tập',
            'Bình Nước 2L'
        ]);
        
        // Tạo category nếu chưa có
        $category = Category::inRandomOrder()->first();
        if (!$category) {
            $category = Category::create([
                'name' => 'Fitness',
                'slug' => 'fitness',
                'description' => 'Sản phẩm thể thao',
                'image' => 'uploads/category/fitness.jpg'
            ]);
        }
        
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'category_id' => Category::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(150000, 2500000), // 150k - 2.5 triệu
            'stock' => $this->faker->numberBetween(10, 100),
            'status' => $this->faker->randomElement(['in_stock', 'out_stock']),
            'unit' => $this->faker->randomElement(['cái', 'hũ', 'túi', 'bình'])
        ];
    }
}