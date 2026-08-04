<?php

namespace App\Jobs;

use App\Models\Notification\Email;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
        $users = User::where('activation', 1)->whereNotNull('email')->get();
        foreach ($users as $user) {
            SendEmailToSingelUser::dispatch($user, $this->email);
        }

        $this->email->update([
            'status' => 'sent',
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        $this->email->update([
            'status' => 'failed',
        ]);
    }
}
