<?php

namespace IHorse\BackendBundle\Tests\Controller;

use IHorse\CategoryBundle\Entity\ItemCategory;
use IHorse\TestBundle\Classes\CustomTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session;
use IHorse\ItemBundle\Entity\Item;

class CategoryControllerTest extends CustomTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $this->createCategories();
        $crawler = $this->client->request('GET', '/bo/categoria/', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "Categoria corner0";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/bo/categoria/crear', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('category[name]' => 'Test',
                'category[description]' => 'description of the category'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "Test";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());

    }

    public function testEdit()
    {
        $this->createCategories();
        $crawler = $this->client->request('GET', '/bo/categoria/editar/1', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $text = "Categoria corner0";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('category[name]' => 'Test',
                'category[description]' => 'description of the category'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "Test";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());

    }

    public function testDelete()
    {
        $this->createCategories();
        $crawler = $this->client->request('GET', '/bo/categoria/borrar/1', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "corner0";
        $this->assertEquals(0, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    private function createCategories()
    {
        $em = $this->entityManager;
        $count = 0;
        while ($count < 2) {
            $category = new ItemCategory();
            $category->setName('Categoria corner' . $count);
            $category->setDescription('Categoría para alquiler de corners en locales');
            $em->persist($category);
            $em->flush();
            $count++;
        }
    }
}