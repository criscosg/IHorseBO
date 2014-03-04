<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class DentalHandler extends RESTHandler
{
    const URL_PATH = 'horses/';
    
    public function getDentals($horseId, $token)
    {
        return parent::getList(self::URL_PATH.$horseId.'/dentals', 'dentals', $token);
    }

    public function getDental($horseId, $id, $token)
    {
        return parent::get(self::URL_PATH.$horseId.'/dentals/'.$id, 'dental', $token);
    }

    public function postDental($horseId, $params, $token)
    {
        return parent::post(self::URL_PATH.$horseId."/dentals", $params, $token);
    }

    public function putDental($horseId, $id, $params, $token)
    {
        return parent::put(self::URL_PATH.$horseId."/dentals/".$id, $params, $token);
    }

    public function deleteDental($horseId, $id, $token)
    {
        return parent::delete(self::URL_PATH.$horseId."/dentals/".$id, $token);
    }
}