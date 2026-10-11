<?php

return [
    'length' => 6,
    'width' => 180,
    'height' => 50,
    'font_size' => 24,
    'angle_min' => -15,
    'angle_max' => 15,
    'noise_lines' => 8,
    'session_key' => 'flocms_captcha',
    'characters' => 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789',
    'case_sensitive' => false,
    // Every verify() removes the code, right or wrong, so it can't be guessed by retrying
    'single_use' => true,
    'fonts' => [
        __DIR__ . '/../public/fonts/Acme.ttf',
        __DIR__ . '/../public/fonts/PlayfairDisplay.ttf',
        __DIR__ . '/../public/fonts/Ubuntu.ttf',
    ],
];