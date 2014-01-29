<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\AdminUserType;

class UserController extends IHorseController
{
    public function listUsersAction()
    {
        $session = $this->getRequest()->getSession();
        $users = $this->get('rest.handler.model')->getList('users', 'users', $session->get('access_token'));

        return $this->render('BackendBundle:Users:index.html.twig', array('users' => $users));
    }
    
    public function userShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $user = $this->get('rest.handler.model')->get('users/'.$id, 'user', $session->get('access_token'));

        return $this->render('BackendBundle:Users:view.html.twig', array('user' => $user));
    }

    public function newUserAction()
    {
        $form = $form = $this->createForm(new AdminUserType());

        return $this->render('BackendBundle:Users:create.html.twig', array('form' => $form->createView()));
    }

    public function editUserAction($id)
    {
        $session = $this->getRequest()->getSession();
        $user = $this->get('rest.handler.model')->get('users/'.$id, 'user', $session->get('access_token'));
        $form = $form = $this->createForm(new AdminUserType(), $user);
    
        return $this->render('BackendBundle:Users:create.html.twig', array('form' => $form->createView(),'edition'=>true, 'id'=>$id));
    }

    public function createUserAction()
    {
        $params=$this->getRequest()->request->get('admin_user');
        $session = $this->getRequest()->getSession();
        $user = $this->get('rest.handler.model')->post("users", array('admin_user'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('users_list'));
    }
    
    public function putUserAction($id)
    {
        $params=$this->getRequest()->request->get('admin_user');
        $session = $this->getRequest()->getSession();
        $user = $this->get('rest.handler.model')->put("users/".$id, array('admin_user'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('users_list'));
    }

    public function deleteUserAction($id)
    {
        $session = $this->getRequest()->getSession();
        $user = $this->get('rest.handler.model')->delete("users/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('users_list'));
    }
}