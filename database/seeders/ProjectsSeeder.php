<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::create([
            'name' => 'Project Alpha (Web app)',
            'description' => 'A web application for managing tasks and projects.',
        ]);

        Project::create([
            'name' => 'Project Beta (Mobile app)',
            'description' => 'A mobile application for managing tasks and projects on the go.',
        ]);

        Project::create([
            'name' => 'Project Gamma (API)',
            'description' => 'An API for managing tasks and projects, to be used by other applications.',
        ]);
    }
}
