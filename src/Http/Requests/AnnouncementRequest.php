<?php

namespace Tino\Announcements\Http\Requests;

use Tino\Http\Requests\Request;

class AnnouncementRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:150',
            'body' => 'required|max:1500',
            'email_notifications' => 'boolean'
        ];
    }
}
