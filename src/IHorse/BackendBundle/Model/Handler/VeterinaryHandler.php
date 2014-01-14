<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class VeterinaryHandler extends RESTHandler
{
    public function getVeterinaries()
    {
        return parent::getList('veterinaries', 'veterinaries');
    }

    public function getVeterinary($id)
    {
        return parent::get('veterinaries/'.$id, 'veterinary');
    }

    public function postVeterinary($params)
    {
        return parent::post("veterinaries", $params);
    }

    public function putVeterinary($id, $params)
    {
        return parent::put("veterinaries/".$id, $params);
    }

    public function deleteVeterinary($id)
    {
        return parent::delete("veterinaries/".$id);
    }
}