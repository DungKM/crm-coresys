<?php

return [
    'default_filters' => [
        'only_new' => true,

        // lead chưa kết thúc
        'exclude_stages' => ['won', 'lost'],

        // nếu có xử lý
        'use_processed_at' => false,
    ],
];

