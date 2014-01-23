<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class HistoryHandler extends RESTHandler
{
    public function getHistories($horseId, $token)
    {
        return parent::getList('horses/'.$horseId['id'].'/histories', 'histories', $token);
    }

    public function getHistory($horseId, $id, $token)
    {
        return parent::get('horses/'.$horseId['id'].'/histories/'.$id, 'history', $token);
    }

    public function postHistory($horseId, $params, $token)
    {
        return parent::post('horses/'.$horseId['id']."/histories", $params, $token);
    }

    public function putHistory($horseId, $id, $params, $token)
    {
        return parent::put('horses/'.$horseId['id']."/histories/".$id, $params, $token);
    }

    public function deleteHistory($horseId, $id, $token)
    {
        return parent::delete('horses/'.$horseId['id']."/histories/".$id, $token);
    }
}