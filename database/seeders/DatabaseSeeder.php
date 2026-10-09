<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // The following users will be created when migrate:fresh

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'is_admin' => true,
        ]);

        User::factory(10)->create();

        $teachers = User::factory()->teacher()->count(3)->create();

        foreach ($teachers as $teacher) {
            Course::factory()->count(2)->create([
                'teacher_id' => $teacher->id,
            ]);
        }

    }
}
