<?php

namespace App\Service\Mail\Contracts;

use App\Service\Mail\Model\Contracts\Receiver;
use App\Service\Mail\Model\Contracts\Sender;
use App\Service\Mail\Model\Contracts\Template;
use Illuminate\Support\Collection;

interface MailContract
{
    function send(Template $template, Sender $from, Receiver $to, UserMailable $mailable);

    function getTemplates(): Collection;
    function getTemplate(string $id): Template;

    function getLog(string $id);
}