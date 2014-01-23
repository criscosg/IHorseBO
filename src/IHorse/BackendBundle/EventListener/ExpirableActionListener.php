<?php
namespace IHorse\BackendBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use IHorse\BackendBundle\Controller\ExpirableControllerInterface;
use IHorse\BackendBundle\Controller\BackendController;
use Symfony\Component\HttpFoundation\Response;

class ExpirableActionListener
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        if (!is_array($controller)) {
            // not a object but a different kind of callable. Do nothing
            return;
        }

        $controllerObject = $controller[0];
        if ($controllerObject instanceof ExpirableControllerInterface) {
            $now = new \DateTime('now');
            if($event->getRequest()->getSession()->get('access_token')){
                if ($event->getRequest()->getSession()->get('expires') < $now->getTimestamp()) {
                    $response = $controllerObject->refreshToken($event->getRequest(), $controller);
                    if ($response) {
                        $newController=$this->callableLoginAction();
                        $event->setController($newController);
                    }
                }
            } else {
                $newController=$this->callableLoginAction();
                $event->setController($newController);
            }
        }

        return;
    }
    
    private function callableLoginAction(){
        $backendController=new BackendController();
        $backendController->setContainer($this->container);

        return array($backendController, 'loginAction');
    }
}