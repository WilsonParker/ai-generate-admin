<?php

namespace Tests\Feature\Image;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Image\Media;
use App\Models\Prompt\Prompt;
use Illuminate\Support\Str;
use Tests\TestCase;

class ImageTest extends TestCase
{
    public function test_media_conversion(): void
    {
        /**
         * @var Prompt $model
         * */
        $models = Prompt::all();
        $models->each(
            fn($model) => $model->getMedia('gallery')
                                ->each(function (Media $media) use ($model) {
                                    $model->addMediaFromDisk($media->getPath(), 'media')
                                          ->preservingOriginal() //middle method
                                          ->usingFileName(md5(Str::of($media->getPath())->afterLast('/')))
                                          ->toMediaCollection('gallery', 'media'); //finishing method;
                                    $media->delete();
                                }));
    }
}
