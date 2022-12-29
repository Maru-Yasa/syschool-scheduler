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
        [
            'text'        => 'Jadwal',
            'icon'        => 'fas fa-fw fa-calendar',
            // 'label'       => 4,
            'submenu'     => [
                [
                    'text' => 'Buat Jadwal',
                    'url'  => 'jadwal/tambah',
                    'icon' => 'fas fa-fw fa-plus'
                ],
                [
                    'text' => 'Lihat Jadwal',
                    'url'  => 'jadwal',
                    'icon' => 'fas fa-fw fa-eye'
                ]
            ],
        ],
        [
            'text' => 'Auto Generate User',
            'url' => 'tools/autoGenerateUser',
            'icon' => 'fas fa-fw fa-users'
        ],
        ['header' => 'MASTER DATA'],
        [
            'text'        => 'Users',
            'url'         => 'user',
            'icon'        => 'fa fa-fw fa-user',
            // 'label'       => Guru::all()->count(),
            'label_color' => 'success',
        ],
        [
            'text'        => 'Guru',
            'url'         => 'guru',
            'icon'        => 'fa fa-fw fa-users',
            // 'label'       => Guru::all()->count(),
            'label_color' => 'success',
        ],
        [
            'text'        => 'Jurusan',
            'url'         => 'jurusan',
            'icon'        => 'fa fa-fw fa-wrench',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Hari',
            'url'         => 'hari',
            'icon'        => 'fa fa-fw fa-calendar',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Kelas',
            'url'         => 'kelas',
            'icon'        => 'fa fa-fw fa-list',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Mata Pelajaran',
            'url'         => 'mapel',
            'icon'        => 'fa fa-fw fa-book',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Ruang Kelas',
            'url'         => 'ruang_kelas',
            'icon'        => 'fa fa-fw fa-landmark',
            // 'label'       => 4,
            'label_color' => 'success',
        ],
        [
            'text'        => 'Semester',
            'url'         => 'semester',
            'icon'        => 'fa fa-fw fa-calendar-alt',
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