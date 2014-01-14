<?php
namespace IHorse\BackendBundle\Model\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\BrowserKit\Request;
use Guzzle\Http\Client;

class AdminUserHandler extends RESTHandler
{
    public function getUsers()
    {
        return parent::getList('users', 'users');
    }
    
    public function getUser($id)
    {
        return parent::get('users/'.$id, 'user');
    }
    
    public function postUser($params)
    {
        return parent::post("users", $params);
    }

    public function putUser($id, $params)
    {
        return parent::put("users/".$id, $params);
    }

    public function deleteUser($id)
    {
        return parent::delete("users/".$id);
    }
}