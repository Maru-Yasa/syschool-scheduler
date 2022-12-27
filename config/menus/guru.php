<?php

return [
    'menu' =>  [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        ['header' => 'SCHEDULER PRO'],
        [
            'text'        => 'Beranda',
            'url'         => 'home',
            'icon'        => 'fas fa-fw fa-home',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url'  => 'profile/settings',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'Logout',
            'url'  => 'profile/logout',
            'icon' => 'fas fa-sign-out-alt',
        ],
    ],
];