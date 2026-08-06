<?php

namespace App\Jobs;

use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Notification\SMS;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Config;

class SendSmsToUsers implements ShouldQueue
{
    use Queueable;
    public $sms;

    /**
     * Create a new job instance.
     */
    public function __construct(SMS $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->sms->update([
            'status' => 'sending',
        ]);

        $jobs = [];
        $smsModel = $this->sms;

        User::whereNotNull('mobile')->chunkById(500, function ($users) use (&$jobs, $smsModel) {
            foreach ($users as $user) {
                $jobs[] = new SendSmsToSingelUser($user, $smsModel);
            }
        });

        if (empty($jobs)) {
            $this->sms->update([
                'status' => 'failed',
            ]);

            return;
        }

        $smsId = $this->sms->id;

        Bus::batch($jobs)
            ->then(function () use ($smsId) {
                SMS::where('id', $smsId)->update([
                    'status' => 'sent',
                ]);
            })
            ->catch(function () use ($smsId) {
                SMS::where('id', $smsId)->update([
                    'status' => 'failed',
                ]);
            })
            ->dispatch();
    }

    public function failed(\Throwable $exception): void
    {
        $this->sms->update([
            'status' => 'failed',
        ]);
    }
}
