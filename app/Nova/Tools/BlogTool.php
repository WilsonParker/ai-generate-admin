<?php

namespace App\Nova\Tools;

use App\Nova\Resources\Blog\Blog;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;


class BlogTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::resources([
            Blog::class,
        ]);
    }


    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function menu(Request $request)
    {

        return [

            MenuSection::make('Blogs', [
                MenuItem::link('Blogs', 'resources/blogs'),
            ])->icon('key')->collapsable(),

        ];
    }
}

