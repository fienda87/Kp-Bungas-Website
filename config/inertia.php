<?php

return [
    'ssr' => [
        'enabled' => false,
        'url' => 'http://127.0.0.1:13714',
    ],
    'pages' => [
        'ensure_pages_exist' => false,
        'paths' => [
            resource_path('js/Pages'),
        ],
        'extensions' => [
            'js', 'jsx', 'svelte', 'ts', 'tsx', 'vue',
        ],
    ],
    'testing' => [
        'ensure_pages_exist' => true,
    ],
];
