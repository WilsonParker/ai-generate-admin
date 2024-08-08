<?php

namespace App\Models\Image\AI;


class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media
{
    protected $connection = 'ai';
    protected $table = 'ai_generate_crawler.media';

}
