<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;
use IHorse\BackendBundle\Util\StringHelper;

class RESTHandler
{
    const URL_PREFIX = 'security/';
    
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getList($path, $dataName = null, $params)
    {
        if (is_array($params)) {
            $get = StringHelper::getQueryArrayFromArray($params);
            $request = $this->client->get(self::URL_PREFIX.$path.$get);
        } else {
            $request = $this->client->get(self::URL_PREFIX.$path."?access_token=".$params);
        }
        $response = $request->send();
        $data = $response->json();

        return $data;
    }

    public function get($path, $dataName, $token = null, $params = null)
    {
        if (!$token) {
            if (is_array($params)) {
                $get = StringHelper::getQueryArrayFromArray($params);
                $request = $this->client->get($path.$get);
            } else {
                $request = $this->client->get($path.$params);
            }
        } else {
            $request = $this->client->get(self::URL_PREFIX.$path."?access_token=".$token);
        }
        $response = $request->send();
        $data = $response->json();

        return $data;
    }

    public function post($path, $params, $token = null)
    {
        if (!$token) {
            $request = $this->client->post($path, array(), $params);
        } else {
            $request = $this->client->post(self::URL_PREFIX.$path."?access_token=".$token, array(), $params);
        }
        $response = $request->send();

        return $response->json();
    }

    public function put($path, $params, $token = null)
    {
        if (!$token) {
            $request = $this->client->put($path, array(), $params);
        } else {
            $request = $this->client->put(self::URL_PREFIX.$path."?access_token=".$token, array(), $params);
        }
        $response = $request->send();

        return $response->json();
    }

    public function patch($path, $params, $token)
    {
        $request = $this->client->patch(self::URL_PREFIX.$path."?access_token=".$token, array(), $params);
        $response = $request->send();

        return $response->json();
    }

    public function delete($path, $token)
    {
        $request = $this->client->delete(self::URL_PREFIX.$path."?access_token=".$token);
        $response = $request->send();

        return $response;
    }
}