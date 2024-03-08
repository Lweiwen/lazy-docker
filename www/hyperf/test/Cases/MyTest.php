<?php

declare(strict_types=1);

namespace HyperfTest\Cases;

use HyperfTest\HttpTestCase;


class MyTest extends HttpTestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function testExample()
    {
        $this->assertTrue(true);
        $res = $this->client->get('/');
        $this->assertSame(0, $res['code']);
        $res = $this->client->get('/', ['user' => 'developer']);

    }
}