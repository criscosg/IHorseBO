<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class DentalHandler extends RESTHandler
{
    public function getDentals($horseId, $token)
    {
        return parent::getList('horses/'.$horseId.'/dentals', 'dentals', $token);
    }

    public function getDental($horseId, $id, $token)
    {
        return parent::get('horses/'.$horseId.'/dentals/'.$id, 'dental', $token);
    }

    public function postDental($horseId, $params, $token)
    {
        return parent::post('horses/'.$horseId."/dentals", $params, $token);
    }

    public function putDental($horseId, $id, $params, $token)
    {
        return parent::put('horses/'.$horseId."/dentals/".$id, $params, $token);
    }

    public function deleteDental($horseId, $id, $token)
    {
        return parent::delete('horses/'.$horseId."/dentals/".$id, $token);
    }
}