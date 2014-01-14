<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\ClinicType;

class ClinicController extends IHorseController
{
    public function listClinicsAction()
    {
        $clinics = $this->get('clinic.handler.model')->getClinics();

        return $this->render('BackendBundle:Clinic:index.html.twig', array('clinics' => $clinics));
    }

    public function clinicShowAction($id)
    {
        $clinic = $this->get('clinic.handler.model')->getClinic($id);

        return $this->render('BackendBundle:Clinic:view.html.twig', array('clinic' => $clinic));
    }

    public function newClinicAction()
    {
        $form = $form = $this->createForm(new ClinicType());

        return $this->render('BackendBundle:Clinic:create.html.twig', array('form' => $form->createView()));
    }

    public function editClinicAction($id)
    {
        $clinic = $this->get('clinic.handler.model')->getClinic($id);
        $form = $form = $this->createForm(new ClinicType(), $clinic);

        return $this->render('BackendBundle:Clinic:create.html.twig', array('form' => $form->createView(),'edition'=>true, 'id'=>$id));
    }

    public function createClinicAction()
    {
        $params=$this->getRequest()->request->get('clinic');
        $clinic = $this->get('clinic.handler.model')->postClinic(array('clinic'=>$params));

        return $this->redirect($this->generateUrl('clinics_list'));
    }

    public function putClinicAction($id)
    {
        $params=$this->getRequest()->request->get('clinic');
        $clinic = $this->get('clinic.handler.model')->putClinic($id, array('clinic'=>$params));

        return $this->redirect($this->generateUrl('clinics_list'));
    }

    public function deleteClinicAction($id)
    {
        $clinic = $this->get('clinic.handler.model')->deleteClinic($id);

        return $this->redirect($this->generateUrl('clinics_list'));
    }
}