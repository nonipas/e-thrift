<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notify Theme
    |--------------------------------------------------------------------------
    |
    | You can change the theme of notifications by specifying the desired theme.
    | By default the theme light is activated, but you can change it by
    | specifying the dark mode. To change theme, update the global variable to `dark`
    |
    */

    'theme' => env('NOTIFY_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | Notification timeout
    |--------------------------------------------------------------------------
    |
    | Defines the number of seconds during which the notification will be visible.
    |
    */

    'timeout' => 5000,

    /*
    |--------------------------------------------------------------------------
    | Preset Messages
    |--------------------------------------------------------------------------
    |
    | Define any preset messages here that can be reused.
    | Available model: connect, drake, emotify, smiley, toast
    |
    */

    'preset-messages' => [
        // An example preset 'user updated' Connectify notification.
        'user-updated' => [
            'message' => 'The user has been updated successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'User Updated',
        ],

        'user-created' => [
            'message' => 'The user has been created successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'User Created',
        ],

        'user-deleted' => [
            'message' => 'The user has been deleted successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'User Deleted',
        ],

        'member-created' => [
            'message' => 'The member has been created successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'Member Created',
        ],

        'member-updated' => [
            'message' => 'The member has been updated successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'Member Updated',
        ],

        'member-deleted' => [
            'message' => 'The member has been deleted successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'Member Deleted',
        ],

        'contribution-created' => [
            'message' => 'The contribution has been created successfully.',
            'type' => 'success',
            'model' => 'connect',
            'title' => 'Contribution Created',
        ],
    ],

];
