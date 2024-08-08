<?php

namespace App\Service\Mail\Model\Contracts;

interface Receiver
{

    public function getName(): string;

    public function getEmail(): string;


}