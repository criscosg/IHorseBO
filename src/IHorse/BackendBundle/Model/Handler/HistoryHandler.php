<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class HistoryHandler extends RESTHandler
{
    public function getHistories($horseId)
    {
        return parent::getList('horses/'.$horseId['horse'].'/histories', 'histories');
    }

    public function getHistory($horseId, $id)
    {
        return parent::get('horses/'.$horseId['horse'].'/histories/'.$id, 'history');
    }

    public function postHistory($horseId, $params)
    {
        return parent::post('horses/'.$horseId['horse']."/histories", $params);
    }

    public function putHistory($horseId, $id, $params)
    {
        return parent::put('horses/'.$horseId['horse']."/histories/".$id, $params);
    }

    public function deleteHistory($horseId, $id)
    {
        return parent::delete('horses/'.$horseId['horse']."/histories/".$id);
    }
}