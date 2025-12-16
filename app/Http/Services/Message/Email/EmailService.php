<?php

namespace App\Http\Services\Message\Email;

use App\Http\Interfaces\MessageInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService implements MessageInterface
{
    private $details;
    private $subject;
    private $from = [
        ['address' => null, 'name' => null,]
    ];
    private $to;
    private $emailFiles;


    public function fire()
    {
        try {
            Mail::to($this->to)->send(
                new MailViewProvider($this->details, $this->subject, $this->from, $this->emailFiles)
            );
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($address, $name)
    {
        $this->from = [
            [
                'address' => $address,
                'name' => $name,
            ]
        ];
    }

    public function getTo()
    {
        return $this->to;
    }
    public function setTo($to)
    {
        $this->to = $to;
    }

    public function getEmailFiles()
    {
        return $this->emailFiles;
    }
    public function setEmailFiles($emailFiles)
    {
        $this->emailFiles = $emailFiles;
    }
}
