<?php

namespace IHorse\BackendBundle\Tests\Controller;

use IHorse\CategoryBundle\Entity\ItemCategory;
use IHorse\TestBundle\Classes\CustomTestCase;
use IHorse\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session;

class UserControllerTest extends CustomTestCase
{
    protected function setUp()
    {
        parent::setUp();
        parent::loadFixture(__DIR__ . "/../Fixtures/city.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/user-to-login.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/user.yml", 'setDataFixtureUser');

    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/bo/usuarios/', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "probe";
        $this->assertEquals(1, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    public function testCreate()
    {
        $crawler = $this->client->request('GET', '/bo/usuarios/crear', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('type' => 'Renter',
                'user_profiletype[email][first]' => 'usuario@prueba.com',
                'user_profiletype[email][second]' => 'usuario@prueba.com',
                'pass' => '123456',
                'user_profiletype[name]' => 'jimmy',
                'user_profiletype[lastname]' => 'crikket',
                'user_profiletype[city]' =>'1',
                'user_profiletype[aboutMe]' => 'Something about me'));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    public function testEdit()
    {
        $crawler = $this->client->request('GET', '/bo/usuarios/editar/2', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Guardar cambios')
            ->form(array('type' => 'Renter',
                'user_profiletype[email][first]' => 'usuario@prueba.com',
                'user_profiletype[email][second]' => 'usuario@prueba.com',
                'user_profiletype[name]' => 'jimmy',
                'user_profiletype[lastname]' => 'crikket',
                'user_profiletype[city]' =>'1',
                'user_profiletype[aboutMe]' => 'Something about me'));
        $crawler = $this->client->submit($form);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

    }

    public function testDelete()
    {
        $crawler = $this->client->request('GET', '/bo/usuarios/borrar/4', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $text = "test";
        $this->assertEquals(0, $crawler->filter('html:contains(' . $text . ')')->count());
    }

    public function testValidate()
    {
        $crawler = $this->client->request('GET', '/bo/usuarios/validate/4', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $crawler = $this->client->followRedirect();
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $user = $this->entityManager->getRepository('UserBundle:User')->findOneBy(array('id' => 4));
        $this->assertNotNull($user);
        $this->assertTrue($user->isValidated());
    }

    protected function setDataFixtureUser($dataArray)
    {
        $this->setDataFixtureObjects('IHorse\\UserBundle\\Entity\\User', $dataArray);
    }
}