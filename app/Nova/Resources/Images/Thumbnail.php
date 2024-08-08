<?php

namespace App\Nova\Resources\Images;

use App\Models\Image\Media;
use App\Models\Prompt\Prompt;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Thumbnail extends Resource
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
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
            Image::make('thumbnail')
                 ->disk(config('media-library.disk_name'))
                 ->detailWidth(250)
                 ->indexWidth(250)
                 ->thumbnail(fn() => $this->getLink())
                 ->disableDownload(),
        ];
    }

    public function fieldsForDetail(NovaRequest $request)
    {
        return [
            Image::make('thumbnail', 'thumbnail')
                 ->disk(config('media-library.disk_name'))
                 ->detailWidth(250)
                 ->indexWidth(250)
                 ->preview(fn($value) => $this->getLink())
                 ->disableDownload(),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            Image::make('Thumbnail')
                 ->store(function (NovaRequest $request, $model) {
                     $upload = $request->thumbnail;
                     $image = $upload->store(config('media-library.prefix') . '/' . $this->id, 'media');
                     return [
                         'name' => $upload->getClientOriginalName(),
                         'file_name' => Str::of($image)->afterLast('/'),
                         'mime_type' => $upload->getMimeType(),
                         'size' => $upload->getSize(),
                     ];
                 }),
        ];

        /*
         Image::make('Thumbnail')
                 ->store(function (NovaRequest $request, $model) {
                     $file = $request->thumbnail;
                     $uploaded = $model->model->addMedia($file)
                                              ->usingFileName(
                                                  md5(
                                                      $file->getClientOriginalName()
                                                  ) . '.' . $file->getClientOriginalExtension()
                                              )
                                              ->toMediaCollection('gallery', config('media-library.disk_name'));
                     $model->model->thumbnail_id = $uploaded->id;
                     $model->model->save();
                     return [
                         'name' => $uploaded->name,
                         'file_name' => $uploaded->file_name,
                         'mime_type' => $uploaded->mime_type,
                         'size' => $uploaded->size,
                     ];
                 }),
         * */
    }

    public function fieldsForCreate(NovaRequest $request)
    {
        $prompt = Prompt::find($request->get('viaResourceId'));
        return [
            Image::make('Thumbnail')
                 ->store(function (NovaRequest $request, $model) use ($prompt) {
                     $id = Media::orderByDesc('id')->limit(1)->first()->id + 1;
                     $file = $request->thumbnail;
                     $upload = $file->store(config('filesystems.path') . '/' . $id, 'media');
                     return [
                         'model_id' => $prompt->getKey(),
                         'model_type' => 'prompt',
                         'name' => $file->getClientOriginalName(),
                         'file_name' => Str::of($upload)->afterLast('/'),
                         'mime_type' => $file->getMimeType(),
                         'size' => $file->getSize(),
                         'collection_name' => 'gallery',
                         'disk' => config('media-library.disk_name'),
                         'conversions_disk' => config('media-library.disk_name'),
                         'manipulations' => [],
                         'custom_properties' => [],
                         'responsive_images' => [],
                         'generated_conversions' => ["gallery-thumbnail" => true],
                     ];
                 }),
            /*Image::make('Thumbnail')
                 ->store(function (NovaRequest $request, $model) use ($prompt) {
                     dd($request);
                     $file = $request->thumbnail;
                     $upload = $file->store(config('filesystems.path') . '/' . $this->id, 'media');
                     return [
                         'model_id' => $prompt->getKey(),
                         'model_type' => 'prompt',
                         'name' => $file->getClientOriginalName(),
                         'file_name' => Str::of($upload)->afterLast('/'),
                         'mime_type' => $file->getMimeType(),
                         'size' => $file->getSize(),
                         'collection_name' => 'gallery',
                         'disk' => config('media-library.disk_name'),
                         'conversions_disk' => config('media-library.disk_name'),
                         'manipulations' => [],
                         'custom_properties' => [],
                         'responsive_images' => [],
                         'generated_conversions' => [],
                     ];
                 }),*/
            /*Images::make('Thumbnail', 'gallery')
                  ->fillUsing(function (NovaRequest $request, $model) use ($prompt) {
                      $model->model_type = 'prompt';
                      $model->model_id = $prompt->getKey();

                      if ($request->get('__media__') != null) {
                          $existId = $request->get('__media__')['gallery'][0];
                          $existModel = Media::findOrFail($existId);

                          $model->collection_name = $existModel->collection_name;
                          $model->name = $existModel->name;
                          $model->file_name = $existModel->file_name;
                          $model->mime_type = $existModel->mime_type;
                          $model->size = $existModel->size;
                          $model->disk = $existModel->disk;
                          $model->manipulations = $existModel->manipulations;
                          $model->custom_properties = $existModel->custom_properties;
                          $model->responsive_images = $existModel->responsive_images;
                          $model->generated_conversions = $existModel->generated_conversions;
                      } else {
                          $file = $request->file('__media__')['gallery'][0];

                          $model->collection_name = 'gallery';
                          $model->name = $file->getClientOriginalName();
                          $model->file_name = md5(
                                  $file->getClientOriginalName()
                              ) . '.' . $file->getClientOriginalExtension();
                          $model->mime_type = $file->getMimeType();
                          $model->size = $file->getSize();
                      }
                      /*$file = $request->file('__media__')['gallery'][0];
                      $uploaded = $prompt->addMedia($file)
                                         ->usingFileName(
                                             md5(
                                                 $file->getClientOriginalName()
                                             ) . '.' . $file->getClientOriginalExtension()
                                         )
                                         ->toMediaCollection('gallery', config('media-library.disk_name'));
                      $prompt->thumbnail_id = $uploaded->id;
                      $prompt->save();
                      $model = $uploaded;*
                  })
                  ->conversionOnIndexView('gallery-thumbnail')
                  ->enableExistingMedia()
                  ->setFileName(function ($originalFilename, $extension, $model) {
                      return md5($originalFilename) . '.' . $extension;
                  })->rules(['required', 'max:1']), // validation rules
            */
            /*Image::make('Thumbnail')
                 ->store(function (NovaRequest $request, $model) use($prompt) {
                     if($model->getKey() != null) {
                        $model->delete();
                     }

                     $file = $request->thumbnail;
                     $uploaded = $prompt->addMedia($file)
                                              ->usingFileName(
                                                  md5(
                                                      $file->getClientOriginalName()
                                                  ) . '.' . $file->getClientOriginalExtension()
                                              )
                                              ->toMediaCollection('gallery', config('media-library.disk_name'));
                     $prompt->thumbnail_id = $uploaded->id;
                     $prompt->save();
                 }),*/

            /*\Laravel\Nova\Fields\Image::make('Media', 'name')
                                      ->disk($disk)
                                      ->preview(fn($value) => $this->getLink())
                                      ->store(function (NovaRequest $request) use ($path) {
                                          $upload = $request->name;
                                          $image = $upload->store($path . '/' . $this->id, 'media');
                                          return [
                                              'name' => $upload->getClientOriginalName(),
                                              'file_name' => Str::of($image)->afterLast('/'),
                                              'mime_type' => $upload->getMimeType(),
                                              'size' => $upload->getSize(),
                                          ];
                                      }),*/
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
