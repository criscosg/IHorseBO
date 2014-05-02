<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\ClinicType;
use IHorse\BackendBundle\Form\ProductClinicType;

class ClinicController extends IHorseController
{
    public function listClinicsAction()
    {
        $session = $this->getRequest()->getSession();
        $clinics = $this->get('rest.handler.model')->getList('clinics', 'clinics', $session->get('access_token'));

        return $this->render('BackendBundle:Clinic:index.html.twig', array('clinics' => $clinics));
    }

    public function clinicShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $clinic = $this->get('rest.handler.model')->get('clinics/'.$id, 'clinic', $session->get('access_token'));

        return $this->render('BackendBundle:Clinic:view.html.twig', array('clinic' => $clinic));
    }

    public function newClinicAction()
    {
        $form = $form = $this->createForm(new ClinicType());

        return $this->render('BackendBundle:Clinic:create.html.twig', array('form' => $form->createView()));
    }

    public function editClinicAction($id)
    {
        $session = $this->getRequest()->getSession();
        $clinic = $this->get('rest.handler.model')->get('clinics/'.$id, 'clinic', $session->get('access_token'));
        $form = $this->createForm(new ClinicType(), $clinic);
        $formProduct = $this->createForm(new ProductClinicType());

        return $this->render('BackendBundle:Clinic:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id,
                                                                            'formProduct' => $formProduct->createView()));
    }

    public function createClinicAction()
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('clinic');
        $clinic = $this->get('rest.handler.model')->post("clinics", array('clinic' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('clinics_list'));
    }

    public function putClinicAction($id)
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('clinic');
        $clinic = $this->get('rest.handler.model')->put("clinics/".$id, array('clinic' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('clinics_list'));
    }

    public function deleteClinicAction($id)
    {
        $session = $this->getRequest()->getSession();
        $clinic = $this->get('rest.handler.model')->delete("clinics/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('clinics_list'));
    }

    public function addProductAction($id)
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('product');
        $clinic = $this->get('rest.handler.model')->post("clinics/".$id.'/products', array('productClinic' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('clinics_list'));
    }
}