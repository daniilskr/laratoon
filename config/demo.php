<?php

return [
    'min_demo_user_id' => 1000,
    'max_demo_user_id' => 2000,

    'scope_hide_other_demo_users_comments' => 'local' !== env('APP_ENV', 'local'),
];
