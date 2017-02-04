<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use MailThief\Testing\InteractsWithMail;


class MailTest extends TestCase
{

    use InteractsWithMail;

    public function testMailConfig()
    {

    }
}
