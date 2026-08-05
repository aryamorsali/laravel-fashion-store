<?php

namespace App\Jobs;

use App\Models\Notification\Email;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class SendEmailToUsers implements ShouldQueue
{
    use Queueable;

    public $email;

    /**
     * Create a new job instance.
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->email->update([
            'status' => 'sending',
        ]);

        $users = User::where('activation', 1)->whereNotNull('email')->get();

        if ($users->isEmpty()) {
            $this->email->update([
                'status' => 'failed',
            ]);

            return;
        }

        $jobs = [];

        foreach ($users as $user) {
            $jobs[] = new SendEmailToSingelUser($user, $this->email);
        }

        $emailId = $this->email->id;

        Bus::batch($jobs)
            ->then(function () use ($emailId) {
                Email::where('id', $emailId)->update([
                    'status' => 'sent',
                ]);
            })
            ->catch(function () use ($emailId) {
                Email::where('id', $emailId)->update([
                    'status' => 'failed',
                ]);
            })
            ->dispatch();
    }

    public function failed(\Throwable $exception): void
    {
        $this->email->update([
            'status' => 'failed',
        ]);
    }
}
