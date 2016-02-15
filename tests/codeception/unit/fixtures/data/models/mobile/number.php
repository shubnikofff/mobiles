<?php
return [
    'number1' => [
        '_id' => 'number1',
        'number' => "1111111111",
        'limit' => 1500,
        'ownerId' => 1,
        'operatorId' => 'operator1',
        'destination'=>\app\modules\mobile\models\Number::DESTINATION_PHONE,
        'options' => [
            'trip',
            'directory',
            'accounting'
        ],
        'history' => [
            [
                'ownerId' => 2,
                'rentDate' => new MongoDate(strtotime('13-10-2013')),
                'returnDate' => new MongoDate(strtotime('15-11-2013')),
            ],
            [
                'ownerId' => 1,
                'rentDate' => new MongoDate(strtotime('13-05-2014'))
            ]
        ],
        'comment' => 'Comment for number 1111111111'
    ],
    'number2' => [
        '_id' => 'number2',
        'number' => "2222222222",
        'limit' => null,
        'ownerId' => 2,
        'operatorId' => 'operator2',
        'destination'=>\app\modules\mobile\models\Number::DESTINATION_MODEM,
        'options' => [],
        'history' => [
            [
                'ownerId' => 2,
                'rentDate' => new MongoDate(strtotime('15-10-2012'))
            ]
        ],
        'comment' => ''
    ],
    'number3' => [
        '_id' => 'number3',
        'number' => "3333333333",
        'limit' => 1000,
        'ownerId' => 3,
        'operatorId' => 'operator1',
        'destination'=>\app\modules\mobile\models\Number::DESTINATION_PHONE,
        'options' => [
            'accounting'
        ],
        'history' => [
            [
                'ownerId' => 3,
                'rentDate' => new MongoDate(strtotime('16-10-2014'))
            ]
        ],
        'comment' => ''
    ],
    'number4' => [
        '_id' => 'number4',
        'number' => "4444444444",
        'limit' => null,
        'ownerId' => null,
        'operatorId' => 'operator1',
        'destination'=>\app\modules\mobile\models\Number::DESTINATION_PHONE,
        'options' => [],
        'history' => [],
        'comment' => ''
    ],
];