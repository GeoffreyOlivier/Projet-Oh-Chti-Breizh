<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ProductControllerTest extends WebTestCase
{
    /** @test */
    public function Index()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/produits');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("menu")')->count()
        );

    }
}
