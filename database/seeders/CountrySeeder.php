<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            [
                'name' => 'Egypt',
                'name_ar' => 'مصر',
                'flag_code' => 'eg',
                'flag_emoji' => '🇪🇬',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'USA',
                'name_ar' => 'الولايات المتحدة',
                'flag_code' => 'us',
                'flag_emoji' => '🇺🇸',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'UAE',
                'name_ar' => 'الإمارات العربية المتحدة',
                'flag_code' => 'ae',
                'flag_emoji' => '🇦🇪',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Saudi Arabia',
                'name_ar' => 'المملكة العربية السعودية',
                'flag_code' => 'sa',
                'flag_emoji' => '🇸🇦',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['flag_code' => $country['flag_code']],
                $country
            );
        }
    }
}
