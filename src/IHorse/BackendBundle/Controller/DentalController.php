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
        $dentals = $this->get('dental.handler.model')->getDentals($horse['id'], $session->get('access_token'));

        return $this->render('BackendBundle:Dental:index.html.twig', array('dentals' => $dentals, 'horse' => $horse));
    }

    public function dentalShowAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $dental = $this->get('dental.handler.model')->getDental($horse, $id, $session->get('access_token'));
        $incisors = $this->get('rest.handler.model')->get('horses/'.$id.'/dental/incisor', null, $session->get('access_token'));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $params=array('access_token'=>$session->get('access_token'), 'dental'=>$id);
        $images = $this->get('rest.handler.model')->getList('imagequadrants', 'imagequadrants', $params);
        foreach ($dental['quadrants'] as $quadrant) {
            $teethQuadrant[] = $this->get('rest.handler.model')->getList('teeth', null, array('access_token' => $session->get('access_token'), 'quadrant' => $quadrant['type']));
        }

        return $this->render('BackendBundle:Dental:view.html.twig', array('dental' => $dental, 'horse' => $horse, 'images' => $images, 'teeth' => $teethQuadrant, 'incisors'=>$incisors));
    }

    public function newDentalAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $form = $this->createForm(new DentalType());
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $quadrants = array('Topleft', 'TopRight', 'Bottomright', 'Bottomleft');
        foreach ($quadrants as $quadrant) {
            $teethQuadrant[] = $this->get('rest.handler.model')->getList('teeth', null, array('access_token' => $session->get('access_token'), 'quadrant' => $quadrant));
        }

        return $this->render('BackendBundle:Dental:create.html.twig', array('form' => $form->createView(),'horse' => $horse, 'teeth' => $teethQuadrant));
    }

    public function editDentalAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dental = $this->get('dental.handler.model')->getDental($horse, $id, $session->get('access_token'));
        $form = $this->createForm(new DentalType(), $dental);

        return $this->render('BackendBundle:Dental:create.html.twig', array('form' => $form->createView(), 'edition' => true, 'id' => $id, 'horse' => $horse, 'dental' => $dental));
    }

    public function createDentalAction($horse)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $dental = $this->get('dental.handler.model')->postDental($horse, $request->request->all(), $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse)));
    }

    public function putDentalAction($horse, $id)
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $dental = $this->get('dental.handler.model')->putDental($horse, $id, $request->request->all(), $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse)));
    }

    public function deleteDentalAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $dental = $this->get('dental.handler.model')->deleteDental($horse, $id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('dental_list', array('horse' => $horse)));
    }
}