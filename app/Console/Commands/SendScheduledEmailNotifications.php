<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailToUsers;
use App\Models\Notification\Email;
use Illuminate\Console\Command;

class SendScheduledEmailNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send-scheduled-email-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emails = Email::where('status', 'scheduled')->where('published_at', '<=', now())->get();

        foreach ($emails as $email) {
            $email->update([
                'status' => 'queued',
            ]);

            SendEmailToUsers::dispatch($email);
        }
    }
}
