<?php

namespace IHorse\BackendBundle\Tests\Controller;

use IHorse\CategoryBundle\Entity\ItemCategory;
use IHorse\TestBundle\Classes\CustomTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session;
use IHorse\ItemBundle\Entity\Item;

class ItemControllerTest extends CustomTestCase
{
    protected function setUp()
    {
        parent::setUp();
        parent::loadFixture(__DIR__ . "/../Fixtures/city.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/user-to-login.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/item.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/item_from_validation.yml");
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/bo/items/', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "test";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/bo/items/crear', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('item[title]' => 'NewItem',
                'item[user]' => 1,
                'item[description]' => 'description of the service',
                'item[metres]' => 30,
                'item[address][city]' =>'',
                'item[address][address]' =>'fake street 123',
                'item[category]' => 1,
                'item[price]' => 50));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "NewItem";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());

    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', '/bo/items/editar/1', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "Name category";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('item[title]' => 'EditItem',
                'item[user]' => 1,
                'item[description]' => 'new description of the service edited',
                'item[metres]' => 35,
                'item[address][city]' =>'1',
                'item[address][address]' =>'fake street 123',
                'item[category]' => 1,
                'item[price]' => 25));
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "new description of the service edited";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());

    }

    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/bo/items/borrar/1', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "test";
        $this->assertEquals(0, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    public function testValidate()
    {

        $crawler = $this->client->request('GET', '/bo/items/validar/2', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $item = $this->entityManager->getRepository('ItemBundle:Item')->findOneBy(array('id' => 2));
        $this->assertNotNull($item);
        $this->assertTrue($item->isValidated());
    }
}