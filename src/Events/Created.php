<?php

namespace Tino\Announcements\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Tino\Announcements\Announcement;

class Created
{
    use Dispatchable;

    /**
     * @var Announcement
     */
    public $announcement;

    /**
     * @var bool
     */
    public $shouldSendEmailNotification;

    public function __construct(Announcement $announcement, $sendEmailNotification = false)
    {
        $this->announcement = $announcement;
        $this->shouldSendEmailNotification = $sendEmailNotification;
    }
}
