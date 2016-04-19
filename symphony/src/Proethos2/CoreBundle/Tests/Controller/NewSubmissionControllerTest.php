<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NewSubmissionControllerTest extends WebTestCase
{
    public function testFirststep()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/submission/new/first');
    }

}
