<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Prompt\Prompt;
use App\Models\Prompt\PromptGenerate;
use App\Models\SellerPayout\SellerPayout;
use App\Service\Mail\Contracts\UserMailable;
use App\Service\Mail\Model\Contracts\Receiver;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends \AIGenerate\Models\User\User implements Receiver, UserMailable
{
    protected $connection = 'api';
    protected $table = 'ai_generate.users';

    public function sellerPromptGenerates(): HasManyThrough
    {
        return $this->hasManyThrough(PromptGenerate::class, Prompt::class, 'user_id', 'prompt_id', 'id', 'id');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMyPageLink(): string
    {
        return '';
    }

    public function sellerPayouts(): HasMany
    {
        return $this->hasMany(SellerPayout::class);
    }

    public function sellerPayout(): HasOne
    {
        return $this->hasOne(SellerPayout::class);
    }
}
