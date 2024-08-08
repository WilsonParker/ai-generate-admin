<?php

namespace App\Service\Mail\Model\Contracts;

interface Template
{
    public function getId(): int;

    public function getName(): string;

    public function getSubject(): string;

    public function getIsActive(): bool;

    public function getSender(): Sender;

    public function getReplyTo(): string;

    public function getToField(): string;

    public function getTag(): string;

    public function getHtmlContent(): string;

    public function getCreatedAt(): string;

    public function getModifiedAt(): string;

}