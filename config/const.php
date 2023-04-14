<?php


return [
    'DEFAULT_BUSINESS_HOURS' => [
        'START' => '10:00:00',
        'END' => '22:00:00',
    ],
    'CHANNEL_ICON' => '/storage/assets/images/channel_icon.png',
    'PROFILE_ICON' => '/storage/assets/images/profile_icon.png',
    'RESERVATION_STATUS' => [
        'TENTATIVE' => [
            'STATUS' => 10,
            'LABEL' => '仮予約',
            'COLOR' => 'dimgray',
        ],
        'CANCEL' => [
            'STATUS' => 50,
            'LABEL' => 'キャンセル',
        ],
        'CLOSE' => [
            'STATUS' => 60,
            'LABEL' => 'お休み',
            'COLOR' => 'gray',
        ],
        'REST' => [
            'STATUS' => 70,
            'LABEL' => '休憩',
            'COLOR' => 'gray',
        ],
        'FIXED' => [
            'STATUS' => 100,
            'LABEL' => '確定',
            'COLOR' => 'green',
        ],
    ],
    'ADMIN_ROLE' => [
        'ADMIN' => [
            'STATUS' => 10,
            'LABEL' => '管理者',
        ],
        'STAFF' => [
            'STATUS' => 20,
            'LABEL' => '一般',
        ],
    ],
    'TRAINER_ROLE' => [
        'TRAINER' => [
            'STATUS' => 10,
            'LABEL' => 'トレーナー',
        ],
        'GENERAL' => [
            'STATUS' => 20,
            'LABEL' => '非表示',
        ],
    ],
    'LINE_STATUS' => [
        // 申請中
        'UNSET' => [
            'STATUS' => NULL,
            'LABEL' => '未設定',
        ],
        // 申請中
        'APPLYING' => [
            'STATUS' => 10,
            'LABEL' => '申請中',
        ],
        // 対応中とテスト中
        'ENTERED' => [
            'STATUS' => 20,
            'LABEL' => '対応中',
        ],
        // 受理
        'ACCOMPLICE' => [
            'STATUS' => 30,
            'LABEL' => '受理',
        ]
    ],
];
