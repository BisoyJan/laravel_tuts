<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{


    public function create(Job $job)
    {
        return view('job_application.create', ['job' => $job]);
    }


    /**
     * Store a newly created job application in storage.
     *
     * @param Job $job The job for which the application is being created.
     * @param Request $request The request data.
     * @return \Illuminate\Http\RedirectResponse Redirects to the job page with a success message.
     */
    public function store(Job $job, Request $request)
    {
        // Create a new job application with the user ID and the expected salary.
        $job->jobApplications()->create([
            'user_id' => $request->user()->id,
            // Validate the expected salary and extract it from the request.
            ...$request->validate([
                'expected_salary' => 'required|min:1|max:1000000',
            ])
        ]);

        // Redirect to the job page with a success message.
        return redirect()->route('jobs.show', $job)
            ->with('success', 'Your application has been submitted.');
    }

    public function destroy(string $id)
    {
        //
    }
}
