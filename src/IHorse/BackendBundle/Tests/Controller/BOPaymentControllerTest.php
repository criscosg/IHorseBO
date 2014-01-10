<?php
namespace IHorse\BackendBundle\Tests\Controller;

use IHorse\TestBundle\Classes\CustomTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session;
use IHorse\BookingBundle\Entity\Booking;

class BOPaymentControllerTest extends CustomTestCase
{
    protected function setUp()
    {
        parent::setUp();
        parent::loadFixture(__DIR__ . "/../Fixtures/city.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/address.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/user.yml", 'setDataFixtureUser');
        parent::loadFixture(__DIR__ . "/../Fixtures/category.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/item.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/user-to-login.yml");
        parent::loadFixture(__DIR__ . "/../Fixtures/booking-to-buy.yml");
    }

    public function testPaymentBo()
    {
        $crawler = $this->client->request('GET', 'bo/payments/create', array(), array(), array('PHP_AUTH_USER' => 'admin', 'PHP_AUTH_PW' => 'adminpass'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->selectButton('Crear')->form(array(
                'transfer[booking]' => 1));
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();
        $this->assertGreaterThan(0, $crawler->filter('html:contains("test")')->count());
    }

    protected function setDataFixtureUser($dataArray)
    {
        $this->setDataFixtureObjects('IHorse\\UserBundle\\Entity\\User', $dataArray);
    }

}