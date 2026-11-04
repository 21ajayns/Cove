<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Local (desktop) mode
    |--------------------------------------------------------------------------
    |
    | The desktop app runs Cove for one person on their own machine: there is no
    | login, everything belongs to a single local user. Because the local server
    | has no login to protect it, every request must carry the secret token that
    | the desktop window generates at launch, so other programs and web pages
    | cannot read the data.
    |
    */

    'local' => (bool) env('COVE_LOCAL', false),

    'local_token' => env('COVE_LOCAL_TOKEN'),

    'local_name' => env('COVE_LOCAL_NAME', 'Me'),

    'local_email' => 'local@cove.local',

];
