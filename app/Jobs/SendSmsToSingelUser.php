<?php

namespace App\Jobs;

use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Notification\SMS;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Config;

class SendSmsToSingelUser implements ShouldQueue
{
    use Queueable, Batchable;

    public $sms;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, SMS $sms)
    {
        $this->user = $user;
        $this->sms = $sms;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // send sms
        $smsService = new SmsService();
        $smsService->setFrom(Config::get('sms.otp_from'));
        $smsService->setTo(['0' . $this->user->mobile]);
        $smsService->setText($this->sms->body);
        $smsService->setIsFlash(true);

        $messagesService = new MessageService($smsService);
        $messagesService->send();
    }
}
