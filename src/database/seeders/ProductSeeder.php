<?php

namespace Database\Seeders;

use App\Types\ProductTypes;
use App\Types\RegionTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.default_country') == RegionTypes::MEXICO){
            DB::table('products')->insert([
                ['item' => 'Donation for a child', 'price' => '50000', 'tangible' => false, 'type' => 'donation', 'description_type' => ProductTypes::DONATION_FOR_A_CHILD],
                ['item' => 'Digital revelation photo', 'price' => '00', 'tangible' => false, 'type' => 'revelation', 'description_type' => ProductTypes::REVELATION_DIGITAL],
            ]);
        }

        if (config('app.default_country') == RegionTypes::BRAZIL){
            DB::table('products')->insert([
                ['item' => 'Donation for a child', 'price' => '6000', 'tangible' => false, 'type' => 'donation', 'description_type' => ProductTypes::DONATION_FOR_A_CHILD],
                ['item' => 'Physical revelation photo', 'price' => '2500', 'tangible' => true, 'type' => 'revelation', 'description_type' => ProductTypes::REVELATION_PHYSICAL],
                ['item' => 'Digital revelation photo', 'price' => '00', 'tangible' => false, 'type' => 'revelation', 'description_type' => ProductTypes::REVELATION_DIGITAL],
            ]);
        }
    }
}
