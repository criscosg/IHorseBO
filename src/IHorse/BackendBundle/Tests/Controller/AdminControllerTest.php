<?php
namespace IHorse\BackendBundle\Test\Controller;

use IHorse\TestBundle\Classes\CustomTestCase;
use Symfony\Component\HttpFoundation\Session;

class AdminControllerTest extends CustomTestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    public function testUserAdminRegistration()
    {
        $crawler = $this->client->request('GET', '/bo/register');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Registrar')
        ->form(array('admin_user[username]' => self::USERNAME,
                'admin_user[password][first]'=>self::PASSWORD,
                'admin_user[password][second]'=>self::PASSWORD));

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $user=$this->entityManager->getRepository('BackendBundle:AdminUser')->findOneBy(array('username'=>self::USERNAME));
        $this->assertNotEquals(null, $user);
    }
}