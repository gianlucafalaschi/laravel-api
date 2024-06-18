<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Technology; // importo il model nel seeder
use Illuminate\Support\Str; // importo Str per usare lo slug

class TechnologiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $technologies = ['css','js','vue','sql','php', 'laravel'];

        foreach($technologies as $technology) {
            $newTechnology = new Technology();
            $newTechnology->name = $technology;
            $newTechnology->slug = Str::slug($newTechnology->name, '-');
            $newTechnology->save();
        }
    }
}
