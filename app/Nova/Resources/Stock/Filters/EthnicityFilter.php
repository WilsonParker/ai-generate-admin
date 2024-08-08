<?php

namespace App\Nova\Resources\Stock\Filters;

use App\Nova\Traits\EnumToSelectOptionsTraits;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use AIGenerate\Models\Stock\Enums\Ethnicity;

class EthnicityFilter extends Filter
{
    use EnumToSelectOptionsTraits;

    public $name = 'ethnicity';

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
        return $query->whereHas('information', fn($query) => $query->where('ethnicity', $value));
    }

    /**
     * Get the filter's available options.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function options(NovaRequest $request)
    {
        return $this->enumToSelectOptionsReverse(Ethnicity::class);
    }

}
