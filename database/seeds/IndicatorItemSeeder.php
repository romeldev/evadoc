<?php

use Illuminate\Database\Seeder;
use App\Model\Indicator;
use App\Model\Item;
use App\Model\Survey;

class IndicatorItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select('delete from indicator_items');
        DB::table('indicator_items')->insert( $this->data() );
    }

    private function data()
    {
        $data = [];

        $indicators = Indicator::where('type_id', Indicator::TYPE_SURVEY)->get();

        foreach($indicators as $indicator)
        {
            $survey = $indicator->evaluation->survey;

            if( $survey->items ){
                $items = $survey->items()->inRandomOrder()
                ->limit( rand(1, $survey->items->count() ) )->get();

                foreach($items as $item){
                    $data[] =  [
                        'indicator_id' => $indicator->id,
                        'item_id' => $item->id,
                    ];
                }
            }
        }

        return $data;
    }
}
