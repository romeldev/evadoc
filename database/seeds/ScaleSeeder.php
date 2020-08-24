<?php

use Illuminate\Database\Seeder;
use App\Model\Scale;

class ScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Scale::query()->delete();
        Scale::insert($this->data());
    }

    public function data()
    {
        $basicScale = [
            'name' => 'Scale 5',
            'options' => json_encode([
                [ 'value' => 1, 'text' => 'Muy en desacuerdo' ],
                [ 'value' => 2, 'text' => 'En desacuerdo' ],
                [ 'value' => 3, 'text' => 'Ni deacuerdo, ni en desacuerdo' ],
                [ 'value' => 4, 'text' => 'Deacuerdo' ],
                [ 'value' => 5, 'text' => 'Muy deacuerdo' ],
            ]),
        ];

        $simpleScale = [
            'name' => 'Scale 3',
            'options' => json_encode([
                [ 'value' => 1, 'text' => 'Malo' ],
                [ 'value' => 2, 'text' => 'Regular' ],
                [ 'value' => 3, 'text' => 'Bueno' ],
            ]),
        ];

        return [ $basicScale, $simpleScale ];
    }

}
