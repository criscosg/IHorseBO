<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class HorseHandler extends RESTHandler
{
    public function getHorses()
    {
        return parent::getList('horses', 'horses');
    }

    public function getHorse($id)
    {
        return parent::get('horses/'.$id, 'horse');
    }

    public function postHorse($params)
    {
        return parent::post("horses", $params);
    }

    public function putHorse($id, $params)
    {
        return parent::put("horses/".$id, $params);
    }

    public function deleteHorse($id)
    {
        return parent::delete("horses/".$id);
    }
}