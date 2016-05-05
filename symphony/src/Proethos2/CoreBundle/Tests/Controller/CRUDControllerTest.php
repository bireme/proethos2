<?php

namespace Proethos2\CoreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CRUDControllerTest extends WebTestCase
{
    public function testListmeeting()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/committee/meeting');
    }

}
