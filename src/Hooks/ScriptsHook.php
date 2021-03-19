<?php

namespace Tino\Announcements\Hooks;

use Tino\Plugins\Contracts\Hook;

class ScriptsHook implements Hook
{
    /**
     * Execute the hook action.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle()
    {
        return view('announcements::partials.scripts');
    }
}
