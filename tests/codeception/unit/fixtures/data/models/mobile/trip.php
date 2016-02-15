<?php
/**
 * Created by PhpStorm.
 * User: bill
 * Date: 13.04.15
 * Time: 16:19
 */
return [
    'trip1' => [
        'numberId' => 'number1',
        'employeeId' => 1,
        'duration' => [
            'from' => new MongoDate(strtotime('01.02.2015')),
            'to' => new MongoDate(strtotime('15.02.2015'))
        ],
        'numberPossession' => [
            'from' => new MongoDate(strtotime('30.01.2015')),
            'to' => new MongoDate(strtotime('17.02.2015'))
        ],
        'complete' => true,
        'destination' => 'Островец'
    ],
    'trip2' => [
        'numberId' => 'number2',
        'employeeId' => 2,
        'duration' => [
            'from' => new MongoDate(strtotime('10.04.2015')),
            'to' => new MongoDate(strtotime('15.05.2015'))
        ],
        'numberPossession' => [
            'from' => new MongoDate(strtotime('09.04.2015')),
            'to' => null
        ],
        'complete' => false,
        'destination' => 'Москва'
    ],
    'trip3' => [
        'numberId' => 'number3',
        'employeeId' => 3,
        'duration' => [
            'from' => new MongoDate(strtotime('08.03.2015')),
            'to' => new MongoDate(strtotime('18.03.2015'))
        ],
        'numberPossession' => [
            'from' => new MongoDate(strtotime('05.03.2015')),
            'to' => new MongoDate(strtotime('25.03.2015')),
        ],
        'complete' => true,
        'destination' => 'Волгодонск'
    ],
];