<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Owner Name (LOCKED)
    |--------------------------------------------------------------------------
    |
    | The business owner's name. Used by ContentPolicyValidator to ensure
    | the name appears ONLY on /about/ and nowhere else.
    |
    | Set via BUILTWELL_OWNER_NAME in .env
    |
    */
    'owner_name' => env('BUILTWELL_OWNER_NAME', ''),

];
