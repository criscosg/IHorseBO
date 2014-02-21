<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;
use IHorse\BackendBundle\Util\StringHelper;

class RESTHandler
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getList($path, $dataName = null, $params)
    {
        if (is_array($params)) {
            $get = StringHelper::getQueryArrayFromArray($params);
            $request = $this->client->get($path.$get);
        } else {
            $request = $this->client->get($path."?access_token=".$params);
        }
        $response = $request->send();
        $data = $response->json();

        return $data;
    }

    public function get($path, $dataName, $token)
    {
        $request = $this->client->get($path."?access_token=".$token);
        $response = $request->send();
        $data = $response->json();

        return $data;
    }

    public function post($path, $params, $token)
    {ldd($token);
        $request = $this->client->post($path."?access_token=".$token, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function put($path, $params, $token)
    {
        $request = $this->client->put($path."?access_token=".$token, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function patch($path, $params, $token)
    {
        $request = $this->client->patch($path."?access_token=".$token, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function delete($path, $token)
    {
        $request = $this->client->delete($path."?access_token=".$token);
        $response = $request->send();

        return $response;
    }
}