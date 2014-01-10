<?php
namespace IHorse\BackendBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IHorseController extends Controller
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

    protected function checkEmailAvailable($email)
    {
        if (is_array($email)) {
            $email = $email['email']['first'];
        }
        $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->findOneBy(array('email' => $email));
        if (!$user) {
            return true;
        }

        return false;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    protected function renderLoginTemplate($template)
    {
        $request = $this->getRequest();
        $sesion = $request->getSession();
        $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR, $sesion->get(SecurityContext::AUTHENTICATION_ERROR));

        return $this->render($template, array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error));
    }

    /**
     * @return \IHorse\UserBundle\Entity\User
     */
    public function getUser()
    {
        return parent::getUser();
    }
}
