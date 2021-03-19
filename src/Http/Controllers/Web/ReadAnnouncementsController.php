<?php

namespace Tino\Announcements\Http\Controllers\Web;

use Tino\Http\Controllers\Controller;

class ReadAnnouncementsController extends Controller
{

    /**
     * Update the timestamp when announcements were last read
     * by the currently authenticated user.
     */
    public function index()
    {
        auth()->user()->forceFill([
            'announcements_last_read_at' => now()
        ])->save();
    }
}
