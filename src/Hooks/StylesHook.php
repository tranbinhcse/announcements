<?php

namespace Tino\Announcements\Hooks;

use Tino\Plugins\Contracts\Hook;

class StylesHook implements Hook
{
    /**
     * Execute the hook action.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle()
    {
        return view('announcements::partials.styles');
    }
}
