<?php

/**
 * @author Ibrahim Maïga <maiga.ibrm@gmail.com>.
 */

return [
        'route1' => [
            'pattern' => [
                'value' => '/begin/{id}/{foo}/{bar}',
            ],
            'defaults' => 'Class\Namespace\FakeClass:fakeMethod',
            'requirements' => [
                'id' => '\d+',
                'foo' => '\w+',
                'bar' => '\w+',
            ]
        ]
];
