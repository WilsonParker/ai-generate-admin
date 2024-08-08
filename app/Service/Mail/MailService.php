<?php

namespace App\Service\Mail;

use App\Service\Mail\Contracts\MailContract;
use App\Service\Mail\Contracts\UserMailable;
use App\Service\Mail\Model\Contracts\Receiver;
use App\Service\Mail\Model\Contracts\Sender;
use App\Service\Mail\Model\Contracts\Template;
use Illuminate\Support\Collection;

class MailService
{
    public function __construct(private readonly MailContract $contract) {}

    public function getTemplates(): \Illuminate\Support\Collection
    {
        return $this->contract->getTemplates();
    }

    public function getTemplate(string $id): Template
    {
        return $this->contract->getTemplate($id);
    }

    public function send(Template $template, Sender $from, Receiver $to, UserMailable $mailable)
    {
        $this->contract->send($template, $from, $to, $mailable);
    }

    public function getLog(string $id) {
        return $this->contract->getLog($id);
    }

    public function getParams(string $content): array
    {
        return $this->contract->getParams($content);
    }
}