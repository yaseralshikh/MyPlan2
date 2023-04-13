<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadmin' => [
            'users' => 'c,r,u,d',
            'pecializations' => 'c,r,u,d',
            'events' => 'c,r,u,d',
            'tasks' => 'c,r,u,d',
            'levels' => 'c,r,u,d',
            'offices' => 'c,r,u,d',
            'semesters' => 'c,r,u,d',
            'weeks' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'admin' => [
            'events' => 'c,r,u,d',
            'users' => 'c,r,u,d',
            'tasks' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'user' => [
            'events' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
