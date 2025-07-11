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
        'super-admin' => [
            'users' => 'c,r,u,d',
            'roles' => 'c,r,u,d',  // Roles && assign permission
            'managers' => 'c,r,u,d',
            'profile' => 'r,u',
            'products' => 'c,r,u,d',
            'upcomingreorders' => 'c,r,u,d',
            'trackingproducts' => 'c,r,u,d',
        ],
        'admin' => [
            'users' => 'r',
            'profile' => 'r,u',
        ],

    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
