<?php

use TomatoPHP\FilamentMediaManager\Http\Resources\FolderResource;
use TomatoPHP\FilamentMediaManager\Http\Resources\FoldersResource;
use TomatoPHP\FilamentMediaManager\Http\Resources\MediaResource;
use TomatoPHP\FilamentMediaManager\Models\Folder;
use TomatoPHP\FilamentMediaManager\Models\Media;

return [
    "model" => [
        "folder" => Folder::class,
        "media" => Media::class,
    ],

    "api" => [
        "active" => false,
        "middlewares" => [
            "api",
            "auth:sanctum"
        ],
        "prefix" => "api/media-manager",
        "resources" => [
            "folders" => FoldersResource::class,
            "folder" => FolderResource::class,
            "media" => MediaResource::class
        ]
    ],

    "user" => [
      'column_name' => 'name',
    ],
];
