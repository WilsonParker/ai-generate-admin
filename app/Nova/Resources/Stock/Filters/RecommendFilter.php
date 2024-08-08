<?php

namespace App\Nova\Resources\Stock\Filters;

use App\Nova\Traits\EnumToSelectOptionsTraits;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class RecommendFilter extends Filter
{
    use EnumToSelectOptionsTraits;

    public $name = 'is_recommend';

    /**
     * Apply the filter to the given query.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     * @param mixed                                   $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        if ($value == 'true') {
            $query->whereHas('recommend');
        } else {
            $query->whereDoesntHave('recommend');
        }
        return $query;
    }

    /**
     * Get the filter's available options.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return [
            'Yes' => true,
            'No' => false,
        ];
    }

}
