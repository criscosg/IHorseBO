<?php

namespace IHorse\BackendBundle\Test\Controller;

use IHorse\TestBundle\Classes\CustomTestCase;

class VeterinaryControllerTest extends CustomTestCase
{
    protected function setUp() {
        parent::setUp();
    }
    
    public function testAll()
    {
        $crawler = $this->client->request('GET', '/veterinaries/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("Test")')->count());
    }

    public function testPost()
    {
        $crawler = $this->client->request('GET', '/veterinaries/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Guardar')
        ->form(array('veterinary[name]' => 'Test',
                'veterinary[email]' => self::USERNAME,
                'veterinary[password]'=>123456,
                'veterinary[last_name]'=>'testeando',
                'veterinary[idiom]' => 'ES'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Test")')->count());
    }

    public function testView()
    {
        $crawler = $this->client->request('GET', '/veterinaries/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-eye')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Vista de veterinario")')->count());
    }

    public function testPut()
    {
        $crawler = $this->client->request('GET', '/veterinaries/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-pencil')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Guardar')
        ->form(array('veterinary[name]' => 'TestActualizado',
                'veterinary[email]' => self::USERNAME,
                'veterinary[password]'=>123456,
                'veterinary[last_name]'=>'testeando',
                'veterinary[idiom]' => 'ES'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
    
        $this->assertGreaterThan(0, $crawler->filter('html:contains("TestActualizado")')->count());
    }

    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/veterinaries/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-trash-o')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("Test")')->count());
    }
}