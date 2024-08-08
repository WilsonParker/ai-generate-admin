<?php

namespace App\Service\Mail\Model;

use App\Service\Mail\Model\Contracts\Sender as SenderContract;
use Illuminate\Database\Eloquent\Model;

class Sender extends Model implements SenderContract
{
    protected $table = 'mail_senders';
    protected $fillable = [
        'id',
        'name',
        'email',
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function set($model, $key, $value, $attributes)
    {
        return $this->fill([
            'id' => $value->getId(),
            'name' => $value->getName(),
            'email' => $value->getEmail(),
        ]);
    }

}