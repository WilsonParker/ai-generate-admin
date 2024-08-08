<?php

namespace App\Service\Mail\Contracts;

use App\Service\Mail\Model\Contracts\Receiver;
use App\Service\Mail\Model\Contracts\SendEmail;
use App\Service\Mail\Model\Contracts\Sender;
use App\Service\Mail\Model\Contracts\Template;
use Illuminate\Support\Collection;

interface MailParameterContract
{
    public function bindParams(Collection $params, UserMailable $mailable): array;
}