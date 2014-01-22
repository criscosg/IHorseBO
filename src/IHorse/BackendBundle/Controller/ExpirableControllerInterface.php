<?php

namespace IHorse\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
interface ExpirableControllerInterface {

    public function refreshToken(Request $request, $controller);

}