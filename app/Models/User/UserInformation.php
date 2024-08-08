<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Services\Image\Models\Media;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\IsSorted;

class UserInformation extends \AIGenerate\Models\User\UserInformation  implements HasMedia
{
    protected $connection = 'api';

    protected $table = 'ai_generate.user_information';

    use InteractsWithMedia, IsSorted;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
             ->useFallbackUrl('/images/test.jpg')
             ->registerMediaConversions(function (Media $media) {
                 $this
                     ->addMediaConversion('avatar-thumbnail')
                     ->fit(Manipulations::FIT_CROP, 300, 300);
             });
    }

    public function getMorphClass(): string
    {
        return 'user-information';
    }

}
