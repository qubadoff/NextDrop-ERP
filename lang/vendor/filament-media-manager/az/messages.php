<?php

return [
    'empty' => [
        'title' => "No Media or Folders Found",
    ],
    'folders' => [
        'title' => 'Fayllar',
        'single' => 'Qovluq',
        'columns' => [
            'name' => 'Ad',
            'collection' => 'Kolleksiya',
            'description' => 'Təsvir',
            'is_public' => 'Hərkəsə açıq ?',
            'has_user_access' => 'Has User Access',
            'users' => 'İstifadəçilər',
            'icon' => 'Icon',
            'color' => 'Rəng',
            'is_protected' => 'Qorunandır ?',
            'password' => 'Şifrə',
            'password_confirmation' => 'Şifrə Təsdiqi',
        ],
        'group' => 'Fayllar',
    ],
    'media' => [
        'title' => 'Media',
        'single' => 'Media',
        'columns' => [
            'image' => 'Şəkil',
            'model' => 'Model',
            'collection_name' => 'Kolleksiya adı',
            'size' => 'Həcm',
            'order_column' => 'Sıra',
        ],
        'actions' => [
            'sub_folder'=> [
              'label' => "Alt qovluq yarat"
            ],
            'create' => [
                'label' => 'Fayl əlavə et',
                'form' => [
                    'file' => 'Fayl',
                    'title' => 'Ad',
                    'description' => 'Təsvir',
                ],
            ],
            'delete' => [
                'label' => 'Qovluqu sil',
            ],
            'edit' => [
                'label' => 'Qovluğu dəyiş',
            ],
        ],
        'notifications' => [
            'create-media' => 'Fayl əlavə olundu',
            'delete-folder' => 'Fayl silindi',
            'edit-folder' => 'Fayl dəyişdirildi',
        ],
        'meta' => [
            'model' => 'Model',
            'file-name' => 'Fayl adı',
            'type' => 'Tip',
            'size' => 'Həcm',
            'disk' => 'Disk',
            'url' => 'URL',
            'delete-media' => 'Faylı sil',
        ],
    ],
];
