<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfigSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ScaleSeeder::class);
        $this->call(LevelSeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(ItemSeeder::class);
        $this->call(EvaluationSeeder::class);
        $this->call(IndicatorSeeder::class);
        $this->call(IndicatorItemSeeder::class);
        // $this->call(QualifySeeder::class);
        // $this->call(ReplySeeder::class);
    }
}
