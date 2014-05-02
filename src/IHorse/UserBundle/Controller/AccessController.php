<?php

namespace IHorse\UserBundle\Controller;

use IHorse\BackendBundle\Controller\CustomController;
use IHorse\UserBundle\Form\Type\NewPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AccessController extends CustomController
{
    public function recoverPasswordAction()
    {
        $formPass = $this->createFormBuilder()->add('email', 'email', array('required' => null))->getForm();

        $peticion = $this->getRequest();
        $candidate = null;
        if ($peticion->getMethod() == 'POST') {
            $formPass->submit($peticion);
            if ($formPass['email']->getData() != null) {
                $name = $formPass['email']->getData();
                $email = array('email' => $name);
                //hacer post si existe ese correo
                $iventiaUser = $this->get('rest.handler.model')->get('register', 'user', null, $email);
                //si existe, mandar el correo
                if ($iventiaUser) {
                    $recover = $this->get('rest.handler.model')->post('changepas', $email);

                    return $this->render('UserBundle:Access:forgotPasswordInstructions.html.twig', array('email'=>$name));
                } else {
                    $this
                        ->setTranslatedFlashMessage(
                            "El correo electrónico proporcionado no pertenece a ninguna cuenta.");
                }
            }
        }

        return $this->render('UserBundle:Access:recoverPassword.html.twig',
            array('formPass' => $formPass->createView()));
    }

    public function newPasswordAction($data = null)
    {
        if ($data == null) {
            $request = Request::createFromGlobals();
            $data = $request->query->get('salt');
        }
        $formPass = $this->createForm(new NewPasswordType());
        $req = $this->getRequest();
        $clave = false;
        $error = false;
        if ($req->getMethod() == 'POST') {
            $formPass->submit($req);
            $saltDefault = $formPass['salt']->getData();
            $candidateRecover = $this->get('rest.handler.model')->get('changepas/', 'user', null, $saltDefault);
            if ($candidateRecover) {
                if ($formPass->isValid()) {
                    $params = array('user' => array('password' => $formPass['password']->getData()), 'salt' => $saltDefault);
                    $changed = $this->get('rest.handler.model')->put('changepa', $params);
                    $this->setTranslatedFlashMessage("Se ha guardado correctamente");

                    return $this->redirect($this->generateUrl('login'));
                } else {
                    $error = true;
                    $this->setTranslatedFlashMessage('Se ha producido un fallo','error');

                    return $this->render('UserBundle:Access:newPassword.html.twig',
                        array(
                            'nameCandidate' => $candidateRecover['email'],
                            'error' => $error,
                            'formPass' => $formPass->createView(),
                            'clave' => $clave,
                            'saltform' => $saltDefault));
                }
            } else {
                $this
                    ->setTranslatedFlashMessage(
                        'No existe ninguna cuenta asociada para recuperar la contraseña',
                        'error');

                return $this->redirect($this->generateUrl('login'));
            }
        } else {
            $candidateRecover = $this->get('rest.handler.model')->get('changepas/', 'user', null, $data);
            if ($candidateRecover) {
                    return $this->render('UserBundle:Access:newPassword.html.twig',
                        array(
                            'nameCandidate' => $candidateRecover['email'],
                            'error' => $error,
                            'formPass' => $formPass->createView(),
                            'clave' => $clave,
                            'saltform' => $data));
            } else {
                $this
                    ->setTranslatedFlashMessage(
                        'No existe ninguna cuenta asociada para recuperar la contraseña, revisa tu correo y sigue las instrucciones que te hemos enviado');

                return $this->redirect($this->generateUrl('login'));
            }
        }
    }
}