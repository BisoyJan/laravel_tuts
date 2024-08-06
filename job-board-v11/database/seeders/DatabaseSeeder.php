<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\Job;
use App\Models\Employer;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Jan Ramil Intong',
            'email' => 'bisoyjan@gmail.com',
        ]);

        User::factory(300)->create();

        $users = User::all()->shuffle();


        for ($i = 0; $i < 20; $i++) {
            Employer::factory()->create([
                'user_id' => $users->pop()->id
            ]);
        }

        $employer = Employer::all();

        for ($i = 0; $i < 100; $i++) {
            Job::factory()->create([
                'employer_id' => $employer->random()->id
            ]);
        }

        foreach ($users as $user) {
            $jobs = Job::inRandomOrder()->take(rand(0, 4))->get();

            foreach ($jobs as $job) {
                JobApplication::factory()->create([
                    'job_id' => $job->id,
                    'user_id' => $user->id
                ]);
            }
        }


    }
}
