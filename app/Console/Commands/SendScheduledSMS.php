<?php

namespace App\Console\Commands;

use App\Jobs\SendSmsToUsers;
use App\Models\Notification\SMS;
use Illuminate\Console\Command;

class SendScheduledSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-scheduled-sms-notifications';

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
       $sms = SMS::where('status', 'scheduled')->where('published_at', '<=', now())->get();

        foreach ($sms as $single_sms) {
            $single_sms->update([
                'status' => 'queued',
            ]);

            SendSmsToUsers::dispatch($single_sms);
        }
    }
}
