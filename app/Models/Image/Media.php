<?php

namespace App\Models\Image;


class Media extends \App\Services\Image\Models\Media
{
    protected $connection = 'api';
    protected $table = 'ai_generate.media';

}
