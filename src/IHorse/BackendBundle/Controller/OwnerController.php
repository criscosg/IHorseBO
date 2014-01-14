<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\OwnerType;

class OwnerController extends IHorseController
{
    public function listOwnersAction()
    {
        $owners = $this->get('owner.handler.model')->getOwners();

        return $this->render('BackendBundle:Owner:index.html.twig', array('owners' => $owners));
    }

    public function ownerShowAction($id)
    {
        $owner = $this->get('owner.handler.model')->getOwner($id);

        return $this->render('BackendBundle:Owner:view.html.twig', array('owner' => $owner));
    }

    public function newOwnerAction()
    {
        $form = $form = $this->createForm(new OwnerType());

        return $this->render('BackendBundle:Owner:create.html.twig', array('form' => $form->createView()));
    }

    public function editOwnerAction($id)
    {
        $owner = $this->get('owner.handler.model')->getOwner($id);
        $form = $form = $this->createForm(new OwnerType(), $owner);

        return $this->render('BackendBundle:Owner:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createOwnerAction()
    {
        $params = $this->getRequest()->request->get('owner');
        $owner = $this->get('owner.handler.model')->postOwner(array('owner' => $params));

        return $this->redirect($this->generateUrl('owners_list'));
    }

    public function putOwnerAction($id)
    {
        $params = $this->getRequest()->request->get('owner');

        $owner = $this->get('owner.handler.model')->putOwner($id, array('owner' => $params));

        return $this->redirect($this->generateUrl('owners_list'));
    }

    public function deleteOwnerAction($id)
    {
        $owner = $this->get('owner.handler.model')->deleteOwner($id);

        return $this->redirect($this->generateUrl('owners_list'));
    }
}