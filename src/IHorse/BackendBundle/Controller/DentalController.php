<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\DentalType;
use IHorse\BackendBundle\Form\FeedingType;
use IHorse\BackendBundle\Form\OwnerType;
use IHorse\BackendBundle\Form\SedationType;
use IHorse\BackendBundle\Form\ToothQuadrantType;
use IHorse\BackendBundle\Form\Type\JsonToothType;
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
        $sign = $this->get('rest.handler.model')->get('horses/'.$id.'/dental/sign', null, $session->get('access_token'));
        $images = $this->get('image.handler.model')->getImagesThumb(array('dental' => $id,'access_token' => $session->get('access_token')));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $params = array('access_token' => $session->get('access_token'), 'dental' => $id);
        $imagesQuadrant = $this->get('rest.handler.model')->getList('imagequadrants', 'imagequadrants', $params);
        foreach ($dental['quadrants'] as $quadrant) {
            $teethQuadrant[] = $this->get('rest.handler.model')->getList('teeth', null, array('access_token' => $session->get('access_token'), 'quadrant' => $quadrant['type']));
        }

        return $this->render('BackendBundle:Dental:view.html.twig', array('dental' => $dental, 'horse' => $horse, 'imagesQuadrant' => $imagesQuadrant, 'teeth' => $teethQuadrant, 'incisors'=>$incisors, 'sign'=>$sign, 'images'=>$images));
    }

    public function newDentalAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $idiom = $this->getRequest()->getLocale();
        $language = $idiom.'_'.strtoupper($idiom);
        $form = $this->createForm(new DentalType());
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $formTeeth = $this->createForm(new ToothQuadrantType());
        //$tooth = $this->get('rest.handler.model')->getList('teeth', 'horse', $session->get('access_token'));
        $teeth1 = $this->get('rest.handler.model')->getList('teeth', 'teeth', array('quadrant' => 'Topleft', 'access_token' => $session->get('access_token')));
        $teeth2 = $this->get('rest.handler.model')->getList('teeth', 'teeth', array('quadrant' => 'Topright', 'access_token' => $session->get('access_token')));
        $teeth3 = $this->get('rest.handler.model')->getList('teeth', 'teeth', array('quadrant' => 'Bottomright', 'access_token' => $session->get('access_token')));
        $teeth4 = $this->get('rest.handler.model')->getList('teeth', 'teeth', array('quadrant' => 'Bottomleft', 'access_token' => $session->get('access_token')));
        $teeth = array('tooth1' => $teeth1, 'tooth2' => $teeth2, 'tooth3' => $teeth3, 'tooth4' => $teeth4);
        $tooth = $this->createTeethDental($teeth);

        return $this->render('BackendBundle:Dental:create.html.twig', array('form' => $form->createView(),'horse' => $horse, 'tooth' => $tooth, 'language' => $language));
    }

    public function editDentalAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $dental = $this->get('dental.handler.model')->getDental($horse, $id, $session->get('access_token'));
        $incisors = $this->get('rest.handler.model')->get('horses/'.$id.'/dental/incisor', null, $session->get('access_token'));
        $sign = $this->get('rest.handler.model')->get('horses/'.$id.'/dental/sign', null, $session->get('access_token'));
        $imagesDental = $this->get('rest.handler.model')->getList('imagequadrants', null, array('dental' => $id,'access_token' => $session->get('access_token')));
        $dataDental = file_get_contents($this->container->getParameter('ihorse.rest.web') . $imagesDental[0]);
        $dataIncisors = file_get_contents($this->container->getParameter('ihorse.rest.web') . $incisors);
        $dataSign = file_get_contents($this->container->getParameter('ihorse.rest.web') . $sign);
        $type = 'octet-stream';
        $incisors = 'data:image/' . $type . ';base64,' . base64_encode($dataIncisors);
        $image = 'data:image/' . $type . ';base64,' . base64_encode($dataDental);
        $sign = 'data:image/' . $type . ';base64,' . base64_encode($dataSign);
        $form = $this->createForm(new DentalType(), $dental);
        $idiom = $this->getRequest()->getLocale();
        $language = $idiom.'_'.strtoupper($idiom);
        $dentalDimension = getimagesize($this->container->getParameter('ihorse.rest.web').$imagesDental[0]);
        $tooth = $this->createTeethRequest($this->get('rest.handler.model')->getList('teeth/dental', null, array('dental' => $id,'access_token' => $session->get('access_token'))));

        return $this->render('BackendBundle:Dental:create.html.twig',
                                array('form' => $form->createView(), 'edition' => true, 'id' => $id, 'horse' => $horse, 'dental' => $dental,
                                      'language' => $language, 'incisors' => $incisors, 'sign' => $sign, 'imageDental' => $image,
                                      'sizeImage' => $dentalDimension[0], 'tooth' => $tooth));
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

    private function createTeethDental($teeth) {
        $tooth = array();
        foreach ($teeth as $quadrant) {
            foreach ($quadrant as $toothDental) {
                $tooth['tooth_'.$toothDental['tooth_number']] = array('salt' => '', 'comment' => '', 'number' =>  $toothDental['tooth_number']);

            }
        }
        return $tooth;
    }

    private function createTeethRequest($teeth) {

        $tooth = array();
            foreach ($teeth as $toothDental) {
                $tooth['tooth_'.$toothDental['tooth']['tooth_number']] = array('salt' => $toothDental['salt'], 'comment' => $toothDental['comment'], 'number' =>  $toothDental['tooth']['tooth_number']);
            }

        return $tooth;
    }
}