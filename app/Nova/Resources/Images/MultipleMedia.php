<?php

namespace App\Nova\Resources\Images;

use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class MultipleMedia extends Resource
{
    public static $displayInNavigation = false;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Image\Media>
     */
    public static $model = \App\Models\Image\Media::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [];
    }

    public function fieldsForIndex(NovaRequest $request)
    {
        return [
            \Laravel\Nova\Fields\Image::make('image')
                                      ->disk('media')
                                      ->detailWidth(250)
                                      ->indexWidth(250)
                                      ->thumbnail(fn() => $this->getLink())
                                      ->disableDownload(),
        ];
    }

    public function fieldsForDetail(NovaRequest $request)
    {
        return [
            \Laravel\Nova\Fields\Image::make('image', 'id')
                                      ->disk('media')
                                      ->detailWidth(250)
                                      ->indexWidth(250)
                                      ->preview(fn($value) => \App\Models\Image\Media::find($value)->getLink())
                                      ->disableDownload(),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request)
    {
        return $this->storeImageField($request);
    }

    public function fieldsForCreate(NovaRequest $request)
    {
        return $this->storeImageField($request);
    }

    private function storeImageField(NovaRequest $request)
    {
        $path = '/images';
        return [
            \Laravel\Nova\Fields\Image::make('Media', 'name')
                                      ->disk('media')
                                      ->store(function (NovaRequest $request) use ($path) {
                                          $upload = $request->name;
                                          $image = $upload->store($path, 'media');
                                          return [
                                              'name' => Str::of($image)->afterLast('/'),
                                              'file_name' => $upload->getClientOriginalName(),
                                              'mime_type' => $upload->getMimeType(),
                                              'size' => $upload->getSize(),
                                          ];
                                      }),
            /*Images::make('prompt', 'gallery')
                  ->enableExistingMedia()
                  ->setFileName(function ($originalFilename, $extension, $model) {
                      return md5($originalFilename) . '.' . $extension;
                  })
                  ->rules(['required', 'max:1']), // validation rules*/
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

}
