<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\Project;
use Illuminate\Support\Str;  // per usare uno degli helper di laravel (qui per usare lo slug)

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // ciclo for per 10 volte crea  una nuova istanza di project, la popola e salva
        for($i = 0; $i < 10; $i++) {
            $newProject = new Project();
            $newProject->name = $faker->sentence(4);
            $newProject->slug = Str::slug($newProject->name, '-');
            $newProject->client_name = $faker->company();
            $newProject->summary = $faker->text(400);
            $newProject->save();
        }
    }
}

/* 
name varchar(200)
slug 	varchar(250)	
client_name varchar(250)
summary text
*/