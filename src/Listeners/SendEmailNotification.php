<?php

namespace Tino\Announcements\Listeners;

use Mail;
use Tino\Announcements\Announcement;
use Tino\Announcements\Events\EmailNotificationRequested;
use Tino\Announcements\Mail\AnnouncementEmail;
use Tino\User;

class SendEmailNotification
{
    /**
     * Handle the event.
     *
     * @param EmailNotificationRequested $event
     * @return void
     */
    public function handle(EmailNotificationRequested $event)
    {
        User::chunk(200, function ($users) use ($event) {
            foreach ($users as $user) {
                $this->sendEmailTo($user, $event->announcement);
            }
        });
    }

    private function sendEmailTo(User $user, Announcement $announcement)
    {
        Mail::to($user)->send(new AnnouncementEmail($announcement));
    }
}
