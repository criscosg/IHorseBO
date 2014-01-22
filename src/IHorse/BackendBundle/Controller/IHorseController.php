<?php
namespace IHorse\BackendBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Guzzle\Http\Exception\BadResponseException;

class IHorseController extends Controller implements ExpirableControllerInterface
{
    protected function resetToken($user, $provider = 'user')
    {
        $token = new UsernamePasswordToken($user, null, $provider, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
        $this->container->get('session')->set("_security_private", serialize($token));
    }

    protected function getHttpJsonResponse($jsonResponse)
    {
        $response = new \Symfony\Component\HttpFoundation\Response($jsonResponse);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    protected function setTranslatedFlashMessage($message, $class = 'info')
    {
        $translatedMessage = $this->get('translator')->trans($message);
        $this->get('session')->getFlashBag()->set($class, $translatedMessage);
    }
    
    public function refreshToken(Request $request, $controller)
    {
        try{
            $restRequest=$this->get('guzzle.client')->get($this->container->getParameter('ihorse.rest.uri').'oauth/v2/token?client_id='.$this->container->getParameter('ihorse.rest.oauth.id').
                    "&client_secret=".$this->container->getParameter('ihorse.rest.oauth.secret')."&grant_type=refresh_token&refresh_token=".
                    $this->get('session')->get('refresh_token'));
            $response=$restRequest->send();
        } catch (BadResponseException $e) {
            return $true;
        }

        $session=$request->getSession();
        $this->saveOauthSession($response->json(), $session);

        return;
    }
    
    private function saveOauthSession($params, $session)
    {
        $session->set('access_token', $params['access_token']);
        $session->set('refresh_token', $params['refresh_token']);
        $now =new \DateTime('now');
        $interval=new \DateInterval('PT'.$params['expires_in'].'S');
        $now->add($interval);
        $session->set('expires', $now->getTimestamp());
    }

}
