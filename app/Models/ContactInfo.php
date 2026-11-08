<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $fillable = [
        'contact_title',
        'contact_title_ar',
        'phone_numbers',
        'email',
        'hours_text',
        'hours_text_ar',
    ];

    protected $casts = [
        'phone_numbers' => 'array',
    ];

    /**
     * Get the first contact info (singleton pattern)
     */
    public static function getInfo()
    {
        $info = self::first();
        if (!$info) {
            // Create default if not exists
            $info = self::create([
                'contact_title' => "Let's Work Together",
                'contact_title_ar' => 'دعنا نعمل معاً',
                'phone_numbers' => ['+20 01200481113', '+20 01287444996'],
                'email' => 'ag.interior.design11@gmail.com',
                'hours_text' => 'Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: By appointment',
                'hours_text_ar' => 'الإثنين - الجمعة: 9:00 صباحاً - 6:00 مساءً<br>السبت: بالحجز',
            ]);
        }
        return $info;
    }
}
