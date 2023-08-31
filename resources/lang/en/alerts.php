<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */

    'backend' => [
        'roles' => [
            'created' => 'The role was successfully created.',
            'deleted' => 'The role was successfully deleted.',
            'updated' => 'The role was successfully updated.',
        ],

        'users' => [
            'cant_resend_confirmation' => 'The application is currently set to manually approve users.',
            'confirmation_email' => 'A new confirmation e-mail has been sent.',
            'confirmed' => 'The user was successfully confirmed.',
            'created' => 'The user successfully created.',
            'deleted' => 'The user was successfully deleted.',
            'deactivated' => 'The user was successfully deactivated.',
            'deleted_permanently' => 'The user was deleted permanently.',
            'restored' => 'The user was successfully restored.',
            'session_cleared' => "The user's session was successfully cleared.",
            'social_deleted' => 'Social Account Successfully Removed',
            'unconfirmed' => 'The user was successfully un-confirmed',
            'updated' => 'The user was successfully updated.',
            'updated_password' => "The user's password was successfully updated.",
            'candidate_evaluated' => "Candidate's ratings & evaluation successfully updated."
        ],
        'featured_jobs' => [
            'created' => 'Featured job was successfully added.',
            'ordered' => 'Featured job order successfully updated.'
        ],
        'homepage_logos' => [
            'created' => 'Homepage logo successfully added.',
            'updated' => 'Homepage logo successfully updated.',
            'ordered' => 'Homepage logo order successfully updated.'
        ],
        'company' => [
            'created' => 'The company successfully added.',
        ],
        'job' => [
            'created' => 'Job successfully added.',
        ],
        'gigs' => [
            'saved' => 'Gig successfully saved.',
        ],
        'referral' => [
            'created' => 'Referral successfully added.',
            'updated' => 'Referral successfully updated.',
        ],
        'settings_saved' => 'System settings successfully saved.',
        'footer_content_save' => 'Footer content successfully saved.',
        'popup_management_save' => 'Popup content successfully saved.',
    ],

    'frontend' => [
        'contact' => [
            'sent' => 'Your information was successfully sent. We will respond back to the e-mail provided as soon as we can.',
        ],
    ],
];
