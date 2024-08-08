<?php

namespace App\Service\Mail\Model\Contracts;

interface Sender
{
    public function getId(): int;

    public function getName(): string;

    public function getEmail(): string;


}