<?php

namespace IHorse\BackendBundle\Test\Controller;

use IHorse\TestBundle\Classes\CustomTestCase;

class ClinicControllerTest extends CustomTestCase
{
    protected function setUp() {
        parent::setUp();
    }
    
    public function testAll()
    {
        $crawler = $this->client->request('GET', '/clinics/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("Test")')->count());
    }

    public function testPost()
    {
        $crawler = $this->client->request('GET', '/clinics/new');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Guardar')
        ->form(array('clinic[name]' => 'Test'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Test")')->count());
    }

    public function testView()
    {
        $crawler = $this->client->request('GET', '/clinics/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-eye')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Clinica")')->count());
    }

    public function testPut()
    {
        $crawler = $this->client->request('GET', '/clinics/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-pencil')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $form = $crawler->selectButton('Guardar')
        ->form(array('clinic[name]' => 'TestActualizado'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
    
        $this->assertGreaterThan(0, $crawler->filter('html:contains("TestActualizado")')->count());
    }

    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/clinics/');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $link = $crawler->filter('a .fa-trash-o')->first()->parents()->first()->link();
        $crawler = $this->client->click($link);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("Test")')->count());
    }
}