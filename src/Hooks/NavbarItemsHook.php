<?php

namespace Tino\Announcements\Hooks;

use Tino\Announcements\Repositories\AnnouncementsRepository;
use Tino\Plugins\Contracts\Hook;

class NavbarItemsHook implements Hook
{
    /**
     * @var AnnouncementsRepository
     */
    private $announcements;

    public function __construct(AnnouncementsRepository $announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * Execute the hook action.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handle()
    {
        $announcements = $this->announcements->latest(5);
        $announcements->load('creator');

        return view('announcements::partials.navbar.list', compact('announcements'));
    }
}
