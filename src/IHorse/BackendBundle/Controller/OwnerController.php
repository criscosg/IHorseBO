<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\OwnerType;
use IHorse\BackendBundle\Form\Search\OwnerSearchType;

class OwnerController extends IHorseController
{
    public function listOwnersAction()
    {
        $form=$this->createForm(new OwnerSearchType(), $this->getRequest()->query->get('search_owner'));
        $form->handleRequest($this->getRequest());
        $session = $this->getRequest()->getSession();
        $params=array('access_token'=>$session->get('access_token'), 'search_owner'=> $this->getRequest()->query->get('search_owner'));
        $owners = $this->get('rest.handler.model')->getList('owners', 'owners', $params);

        return $this->render('BackendBundle:Owner:index.html.twig', array('owners' => $owners, 'form'=>$form->createView()));
    }

    public function ownerShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $owner = $this->get('rest.handler.model')->get('owners/'.$id, 'owner', $session->get('access_token'));

        return $this->render('BackendBundle:Owner:view.html.twig', array('owner' => $owner));
    }

    public function newOwnerAction()
    {
        $form = $form = $this->createForm(new OwnerType());

        return $this->render('BackendBundle:Owner:create.html.twig', array('form' => $form->createView()));
    }

    public function editOwnerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $owner = $this->get('rest.handler.model')->get('owners/'.$id, 'owner', $session->get('access_token'));
        $form = $form = $this->createForm(new OwnerType(), $owner);

        return $this->render('BackendBundle:Owner:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createOwnerAction()
    {
        $params = $this->getRequest()->request->get('owner');
        $session = $this->getRequest()->getSession();
        $owner = $this->get('rest.handler.model')->post("owners", array('owner'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('owners_list'));
    }

    public function putOwnerAction($id)
    {
        $params = $this->getRequest()->request->get('owner');
        $session = $this->getRequest()->getSession();
        $owner = $this->get('rest.handler.model')->put("owners/".$id, array('owner'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('owners_list'));
    }

    public function deleteOwnerAction($id)
    {
        $session = $this->getRequest()->getSession();
        $owner = $this->get('rest.handler.model')->delete("owners/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('owners_list'));
    }
}