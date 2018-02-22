<?php

return [
    'apikey' => env('MC_KEY'),
    'contact' => [
        'company' => env('MC_CONTACT_COMPANY'),
        'address1' => env('MC_CONTACT_ADDRESS1'),
        'address2' => env('MC_CONTACT_ADDRESS2'),
        'city' => env('MC_CONTACT_CITY'),
        'state' => env('MC_CONTACT_STATE'),
        'zip' => env('MC_CONTACT_ZIP'),
        'country' => env('MC_CONTACT_COUNTRY'),
        'phone' => env('MC_CONTACT_PHONE')
    ],
    'permission_reminder' => env('MC_PERM_REMINDER'),
    'campaign_defaults' => [
        'from_name' => env('MC_FROM_NAME'),
        'from_email' => env('MC_FROM_EMAIL'),
        'subject' => 'Untitled',
        'language' => 'en'
    ]
];
