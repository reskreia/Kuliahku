<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mimins')->insert([
            'name' => 'Mimin',
            'email' => 'm@pp.dev',
            'password' => bcrypt('kuliahku2017')
        ]);
    }
}
