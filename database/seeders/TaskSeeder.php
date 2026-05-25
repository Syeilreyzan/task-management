<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectAlpha = Project::where('name', 'Project Alpha (Web app)')->first();
        $projectBeta = Project::where('name', 'Project Beta (Mobile app)')->first();
        $projectGamma = Project::where('name', 'Project Gamma (API)')->first();

        Task::create([
            'project_id' => $projectAlpha->id,
            'name' => 'Implement the backend',
            'priority' => 2,
        ]);

        Task::create([
            'project_id' => $projectAlpha->id,
            'name' => 'Deploy the application',
            'priority' => 3,
        ]);

        Task::create([
            'project_id' => $projectBeta->id,
            'name' => 'Set up the database',
            'priority' => 1,
        ]);

        Task::create([
            'project_id' => $projectBeta->id,
            'name' => 'Build the mobile UI',
            'priority' => 2,
        ]);

        Task::create([
            'project_id' => $projectBeta->id,
            'name' => 'Connect to Backend API',
            'priority' => 3,
        ]);

        Task::create([
            'project_id' => $projectBeta->id,
            'name' => 'Deploy the mobile application',
            'priority' => 4,
        ]);

        Task::create([
            'project_id' => $projectGamma->id,
            'name' => 'Write the API documentation',
            'priority' => 1,
        ]);

        Task::create([
            'project_id' => $projectGamma->id,
            'name' => 'Implement the API endpoints',
            'priority' => 2,
        ]);

        Task::create([
            'project_id' => $projectGamma->id,
            'name' => 'Test the API',
            'priority' => 3,
        ]);
    }
}
