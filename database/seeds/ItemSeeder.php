<?php

use Illuminate\Database\Seeder;
use App\Model\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::query()->delete();
        factory(Item::class, 20)->create();
    }
}
