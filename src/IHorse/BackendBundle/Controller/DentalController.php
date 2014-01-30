<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\DentalType;
use IHorse\BackendBundle\Form\FeedingType;
use IHorse\BackendBundle\Form\SedationType;
use Symfony\Component\HttpFoundation\Request;

class DentalController extends IHorseController
{
    public function listDentalAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dentals = $this->get('dental.handler.model')->getDentals($horse, $session->get('access_token'));

        return $this->render('BackendBundle:Dental:index.html.twig', array('dentals' => $dentals, 'horse' => $horse));
    }

    public function dentalShowAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $dental = $this->get('dental.handler.model')->getDental($horse, $id, $session->get('access_token'));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));

        return $this->render('BackendBundle:Dental:view.html.twig', array('dental' => $dental, 'horse' => $horse));
    }

    public function newDentalAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $form = $this->createForm(new DentalType());
        $formFeed = $this->createForm( new FeedingType());
        $formSedation = $this->createForm(new SedationType());
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));


        return $this->render('BackendBundle:Dental:create.html.twig', array('form' => $form->createView(), 'formFeeding' => $formFeed->createView(),
                                                                            'formSedation' => $formSedation->createView(),'horse' => $horse));
    }

    public function editDentalAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dental = $this->get('dental.handler.model')->getDental($horse, $id, $session->get('access_token'));
        $form = $this->createForm(new DentalType(), $dental);
        $formFeed = $this->createForm( new FeedingType(), $dental['feeding']);
        $formSedation = $this->createForm(new SedationType(), $dental['sedation']);

        return $this->render('BackendBundle:Dental:create.html.twig', array('form' => $form->createView(), 'formFeeding' => $formFeed->createView(),
                                                                            'formSedation' => $formSedation->createView(),
                                                                            'edition' => true, 'id' => $id, 'horse' => $horse, 'dental' => $dental));
    }

    public function createDentalAction($horse)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dental = $this->get('dental.handler.model')->postDental($horse, $request->request->all(), $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse['id'])));
    }

    public function putDentalAction($horse, $id)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dental = $this->get('dental.handler.model')->putDental($horse, $id, $request->request->all(), $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse['id'])));
    }

    public function deleteDentalAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $dental = $this->get('dental.handler.model')->deleteDental($horse, $id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse)));
    }
}