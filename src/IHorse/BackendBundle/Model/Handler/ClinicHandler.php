<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class ClinicHandler extends RESTHandler
{
    public function getClinics()
    {
        return parent::getList('clinics', 'clinics');
    }

    public function getClinic($id)
    {
        return parent::get('clinics/'.$id, 'clinic');
    }

    public function postClinic($params)
    {
        return parent::post("clinics", $params);
    }

    public function putClinic($id, $params)
    {
        return parent::put("clinics/".$id, $params);
    }

    public function deleteClinic($id)
    {
        return parent::delete("clinics/".$id);
    }
}