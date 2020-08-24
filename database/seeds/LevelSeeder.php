<?php

use Illuminate\Database\Seeder;
use App\Model\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Level::query()->delete();
        Level::insert($this->data());
    }

    public function data()
    {
        $poolLevel = [
            'name' => 'Level 5P',
            'intervals' => json_encode([
                [ 'g1' => '1', 'g2' => '0', 'v1' => 4, 'v2' => 5, 'value' => 'Excelente', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 3, 'v2' => 4, 'value' => 'Competente', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 2, 'v2' => 3, 'value' => 'Basico', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 0, 'v2' => 2, 'value' => 'Inicial', 'colour'=> Level::generateColour() ],
            ]),
        ];

        $evaluationLevel = [
            'name' => 'Level 20P',
            'intervals' => json_encode([
                [ 'g1' => '1', 'g2' => '0', 'v1' => 17, 'v2' => 20, 'value' => 'Excelente', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 14, 'v2' => 17, 'value' => 'Competente', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 11, 'v2' => 14, 'value' => 'Basico', 'colour'=> Level::generateColour() ],
                [ 'g1' => '0', 'g2' => '1', 'v1' => 0, 'v2' => 11, 'value' => 'Inicial', 'colour'=> Level::generateColour() ],
            ]),
        ];

        return [ $poolLevel, $evaluationLevel ];
        
    }
}
