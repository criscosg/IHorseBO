<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\AdminUserType;

class UserController extends IHorseController
{
    public function listUsersAction()
    {
        $users = $this->get('user.model')->getUsers();

        return $this->render('BackendBundle:Users:index.html.twig', array('users' => $users));
    }
    
    public function userShowAction($id)
    {
        $user = $this->get('user.model')->getUser($id);

        return $this->render('BackendBundle:Users:view.html.twig', array('user' => $user));
    }

    public function newUserAction()
    {
        $form = $form = $this->createForm(new AdminUserType());

        return $this->render('BackendBundle:Users:create.html.twig', array('form' => $form->createView()));
    }

    public function editUserAction($id)
    {
        $user = $this->get('user.model')->getUser($id);
        $form = $form = $this->createForm(new AdminUserType(), $user);
    
        return $this->render('BackendBundle:Users:create.html.twig', array('form' => $form->createView(),'edition'=>true, 'id'=>$id));
    }

    public function createUserAction()
    {
        $params=$this->getRequest()->request->get('admin_user');
        $user = $this->get('user.model')->postUser(array('admin_user'=>$params));

        return $this->redirect($this->generateUrl('users_list'));
    }
    
    public function putUserAction($id)
    {
        $params=$this->getRequest()->request->get('admin_user');
        $user = $this->get('user.model')->putUser($id, array('admin_user'=>$params));

        return $this->redirect($this->generateUrl('users_list'));
    }

    public function deleteUserAction($id)
    {
        $user = $this->get('user.model')->deleteUser($id);

        return $this->redirect($this->generateUrl('users_list'));
    }
}