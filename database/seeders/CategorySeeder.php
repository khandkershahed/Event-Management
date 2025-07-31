<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Politics',
                'bangla_name' => 'রাজনীতি',
                'slug'        => Str::slug('Politics'),
                'children'    => [
                    ['name' => 'Elections', 'bangla_name' => 'নির্বাচন'],
                    ['name' => 'Government', 'bangla_name' => 'সরকার'],
                ],
            ],
            [
                'name'        => 'Sports',
                'bangla_name' => 'খেলাধুলা',
                'slug'        => Str::slug('Sports'),
                'children'    => [
                    ['name' => 'Football', 'bangla_name' => 'ফুটবল'],
                    ['name' => 'Cricket', 'bangla_name' => 'ক্রিকেট'],
                    ['name' => 'Olympics', 'bangla_name' => 'অলিম্পিক'],
                ],
            ],
            [
                'name'        => 'Technology',
                'bangla_name' => 'প্রযুক্তি',
                'slug'        => Str::slug('Technology'),
                'children'    => [
                    ['name' => 'Gadgets', 'bangla_name' => 'গ্যাজেট'],
                    ['name' => 'Mobile', 'bangla_name' => 'মোবাইল'],
                ],
            ],
            [
                'name'        => 'Entertainment',
                'bangla_name' => 'বিনোদন',
                'slug'        => Str::slug('Entertainment'),
                'children'    => [
                    ['name' => 'Movies', 'bangla_name' => 'সিনেমা'],
                    ['name' => 'Celebrities', 'bangla_name' => 'তারকা'],
                ],
            ],
            [
                'name'        => 'Business',
                'bangla_name' => 'ব্যবসা',
                'slug'        => Str::slug('Business'),
                'children'    => [
                    ['name' => 'Stock Market', 'bangla_name' => 'শেয়ার বাজার'],
                    ['name' => 'Finance', 'bangla_name' => 'অর্থ'],
                ],
            ],
        ];

        foreach ($categories as $cat) {
            $parent = Category::create([
                'name'         => $cat['name'],
                'bangla_name'  => $cat['bangla_name'],
                'slug'         => $cat['slug'],
                'code'         => strtoupper(substr($cat['name'], 0, 3)) . '001',
                'serial'       => rand(1, 99),
                'logo'         => null,
                'image'        => null,
                'banner_image' => null,
                'description'  => "{$cat['name']} category description.",
                'status'       => 'active',
            ]);

            if (!empty($cat['children'])) {
                foreach ($cat['children'] as $child) {
                    Category::create([
                        'parent_id'    => $parent->id,
                        'name'         => $child['name'],
                        'bangla_name'  => $child['bangla_name'],
                        'slug'         => Str::slug($child['name']),
                        'code'         => strtoupper(substr($child['name'], 0, 3)) . rand(100, 999),
                        'serial'       => rand(1, 99),
                        'logo'         => null,
                        'image'        => null,
                        'banner_image' => null,
                        'description'  => "{$child['name']} subcategory of {$cat['name']}.",
                        'status'       => 'active',
                    ]);
                }
            }
        }
    }
}
