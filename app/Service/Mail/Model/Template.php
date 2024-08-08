<?php

namespace App\Service\Mail\Model;

use App\Service\Mail\Model\Contracts\Sender;
use App\Service\Mail\Model\Contracts\Template as TemplateContract;
use Illuminate\Database\Eloquent\Model;

class Template extends Model implements TemplateContract
{

    protected $table = 'mail_templates';
    protected $fillable = [
        'id',
        'name',
        'subject',
        'is_active',
        'test_sent',
        'reply_to',
        'to_field',
        'tag',
        'html_content',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sender' => \App\Service\Mail\Model\Sender::class,
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function getSender(): Sender
    {
        return $this->sender;
    }

    public function getReplyTo(): string
    {
        return $this->reply_to;
    }

    public function getToField(): string
    {
        return $this->to_field;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function getHtmlContent(): string
    {
        return $this->html_content;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getModifiedAt(): string
    {
        return $this->updated_at;
    }

}