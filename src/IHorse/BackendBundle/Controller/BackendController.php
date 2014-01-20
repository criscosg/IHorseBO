<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use Guzzle\Http\Message\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class BackendController extends IHorseController
{
    public function exampleAction()
    {
        $request=$this->getRequest();
        $token=$request->query->get('access_token');
        $session = $request->getSession();
        $session->set('token', $token);
        
        return $this->render('BackendBundle:Backend:example.html.twig');
    }

    public function loginAction()
    {
        return $this->renderLoginTemplate('BackendBundle:Backend:login.html.twig');
    }

    public function listUsersAction()
    {
        $users = $this->get('user.model')->getUsers();
    }
}
