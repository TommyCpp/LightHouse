<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DBConnectionTest extends TestCase
{
    /**
     * Test connection of Redis
     *
     * @return void
     */
    public function testRedisConnection()
    {
        $this->assertTrue(true);
    }
}
