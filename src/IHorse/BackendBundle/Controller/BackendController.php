<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;

class BackendController extends IHorseController
{
    public function exampleAction()
    {
        return $this->render('BackendBundle:Backend:example.html.twig');
    }

    public function loginAction()
    {
        return $this->renderLoginTemplate('BackendBundle:Backend:login.html.twig');
    }

}
