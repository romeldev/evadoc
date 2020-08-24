<?php

use Illuminate\Database\Seeder;

use App\Model\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::query()->delete();
        Config::insert( $this->data() );
    }

    public function data()
    {
        return [
            [ 'type' => Config::TYPE_VAR, 'key'=> 'SYSTEM_ACTIVE', 'value'=> '1', 'label'=>'system status' ],
            [ 'type' => Config::TYPE_VAR, 'key'=> 'CHECK_STUDENT', 'value'=> '1', 'label'=>'student verification' ],
            [ 'type' => Config::TYPE_CONST, 'key'=> 'CACHE_CONFIG', 'value'=> 'config', 'label'=>'cache config' ],
            [ 'type' => Config::TYPE_CONST, 'key'=> 'CACHE_MENU', 'value'=> 'menus', 'label'=>'cache menu' ],
            [ 'type' => Config::TYPE_CONST, 'key'=> 'CACHE_TEACHERS', 'value'=> 'teachers', 'label'=>'cache teacher' ],
            [ 'type' => Config::TYPE_CONST, 'key'=> 'CACHE_SCHOOLS', 'value'=> 'schools', 'label'=>'cache school' ],
        ];
    }
}
