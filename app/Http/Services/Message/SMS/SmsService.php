<?php

namespace App\Http\Services\Message\SMS;

use App\Http\Interfaces\MessageInterface;
use App\Http\Services\Message\SMS\MeliPayamakService;

class SmsService implements MessageInterface
{
    private $from;
    private $text;
    private $to;
    private $isFlash = true;

    public function fire()
    {
        $meliPayamak = new MeliPayamakService();
        return $meliPayamak->sendSmsSoapClient($this->from, $this->to, $this->text, $this->isFlash);
    }

    public function setFrom($from)
    {
        return $this->from = $from;
    }

    public function setText($text)
    {
        return $this->text = $text;
    }

    public function setTo($to)
    {
        return $this->to = $to;
    }

    public function setIsFlash($flash)
    {
        return $this->isFlash = $flash;
    }
}
