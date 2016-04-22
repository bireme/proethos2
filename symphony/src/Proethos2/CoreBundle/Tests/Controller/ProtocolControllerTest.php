<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProtocolControllerTest extends WebTestCase
{
    public function testShowprotocol()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/protocol/');
    }

}
