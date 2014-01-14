<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class OwnerHandler extends RESTHandler
{
    public function getOwners()
    {
        return parent::getList('owners', 'owners');
    }

    public function getOwner($id)
    {
        return parent::get('owners/'.$id, 'owner');
    }

    public function postOwner($params)
    {
        return parent::post("owners", $params);
    }

    public function putOwner($id, $params)
    {
        return parent::put("owners/".$id, $params);
    }

    public function deleteOwner($id)
    {
        return parent::delete("owners/".$id);
    }
}