<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\VeterinaryType;

class VeterinaryController extends IHorseController
{
    public function listVeterinariesAction()
    {
        $veterinaries = $this->get('veterinary.handler.model')->getVeterinaries();

        return $this->render('BackendBundle:Veterinary:index.html.twig', array('veterinaries' => $veterinaries));
    }

    public function veterinaryShowAction($id)
    {
        $veterinary = $this->get('veterinary.handler.model')->getVeterinary($id);

        return $this->render('BackendBundle:Veterinary:view.html.twig', array('veterinary' => $veterinary));
    }

    public function newVeterinaryAction()
    {
        $form = $form = $this->createForm(new VeterinaryType());

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView()));
    }

    public function editVeterinaryAction($id)
    {
        $veterinary = $this->get('veterinary.handler.model')->getVeterinary($id);
        $form = $form = $this->createForm(new VeterinaryType(), $veterinary);

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView(),'edition'=>true, 'id'=>$id));
    }

    public function createVeterinaryAction()
    {
        $params=$this->getRequest()->request->get('veterinary');
        $veterinary = $this->get('veterinary.handler.model')->postVeterinary(array('veterinary'=>$params));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function putVeterinaryAction($id)
    {
        $params=$this->getRequest()->request->get('veterinary');
        $veterinary = $this->get('veterinary.handler.model')->putVeterinary($id, array('veterinary'=>$params));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function deleteVeterinaryAction($id)
    {
        $veterinary = $this->get('veterinary.handler.model')->deleteVeterinary($id);

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }
}