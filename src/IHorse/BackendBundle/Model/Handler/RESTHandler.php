<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

abstract class RESTHandler
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getList($path, $dataName)
    {
        $request = $this->client->get($path);
        $response = $request->send();
        $data = $response->json();

        return $data[$dataName];
    }

    public function get($path, $dataName)
    {
        $request = $this->client->get($path);
        $response = $request->send();
        $data = $response->json();

        return $data[$dataName];
    }

    public function post($path, $params)
    {
        $request = $this->client->post($path, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function put($path, $params)
    {
        $request = $this->client->put($path, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function delete($path)
    {
        $request = $this->client->delete($path);
        $response = $request->send();

        return $response->json();
    }
}