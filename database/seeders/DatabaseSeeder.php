<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\UserAdminSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // php artisan db:seed --class=UserAdminSeeder
        
        // User::factory(10)->create();

        // $projectAlpha = Project::create([
        //     'name' => 'Project Alpha (Web app)',
        // ]);

        // $projectBeta = Project::create([
        //     'name' => 'Project Beta (Mobile app)',
        // ]);

        // $projectGamma = Project::create([
        //     'name' => 'Project Gamma (API)',
        // ]);

        // Task::create([
        //     'project_id' => $projectAlpha->id,
        //     'name' => 'Design the UI',
        //     'priority' => 1,
        // ]);

        // Task::create([
        //     'project_id' => $projectAlpha->id,
        //     'name' => 'Implement the backend',
        //     'priority' => 2,
        // ]);

        // Task::create([
        //     'project_id' => $projectAlpha->id,
        //     'name' => 'Deploy the application',
        //     'priority' => 3,
        // ]);

        // Task::create([
        //     'project_id' => $projectBeta->id,
        //     'name' => 'Set up the database',
        //     'priority' => 1,
        // ]);

        // Task::create([
        //     'project_id' => $projectBeta->id,
        //     'name' => 'Build the mobile UI',
        //     'priority' => 2,
        // ]);

        // Task::create([
        //     'project_id' => $projectBeta->id,
        //     'name' => 'Connect to Backend API',
        //     'priority' => 3,
        // ]);

        // Task::create([
        //     'project_id' => $projectBeta->id,
        //     'name' => 'Deploy the mobile application',
        //     'priority' => 4,
        // ]);

        // Task::create([
        //     'project_id' => $projectGamma->id,
        //     'name' => 'Write the API documentation',
        //     'priority' => 1,
        // ]);

        // Task::create([
        //     'project_id' => $projectGamma->id,
        //     'name' => 'Implement the API endpoints',
        //     'priority' => 2,
        // ]);

        // Task::create([
        //     'project_id' => $projectGamma->id,
        //     'name' => 'Test the API',
        //     'priority' => 3,
        // ]);
    }
}
