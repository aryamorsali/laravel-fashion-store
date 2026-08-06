<?php

namespace App\Jobs;

use App\Http\Services\Message\Email\EmailService;
use App\Http\Services\Message\MessageService;
use App\Models\Notification\Email;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batchable;

class SendEmailToSingelUser implements ShouldQueue
{
    use Queueable, Batchable;

    public $email;
    public $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, Email $email)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // send email
        $emailService = new EmailService();
        $details = [
            'title' => $this->email->subject,
            'body' => $this->email->body
        ];
        $files = $this->email?->files;
        $filePaths = [];
        foreach ($files as $file) {
            array_push($filePaths, $file->file_path);
        }
        $emailService->setDetails($details);
        $emailService->setFrom('noreply@shop.com', 'CozaShop');
        $emailService->setSubject($this->email->subject);
        $emailService->setTo($this->user->email);
        $emailService->setEmailFiles($filePaths);


        $messagesService = new MessageService($emailService);
        $messagesService->send();
    }
}
