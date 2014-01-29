<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HorseType;
use IHorse\BackendBundle\Form\Search\HorseSearchType;
use IHorse\BackendBundle\Util\StringHelper;

class HorseController extends IHorseController
{
    public function listHorsesAction()
    {
        $form = $this->createForm(new HorseSearchType(), $this->getRequest()->query->get('search_horse'));
        $form->handleRequest($this->getRequest());
        $session = $this->getRequest()->getSession();
        $params = array('access_token'=>$session->get('access_token'), 'search_horse'=> $this->getRequest()->query->get('search_horse'));
        $horses = $this->get('rest.handler.model')->getList('horses', 'horses', $params);

        return $this->render('BackendBundle:Horse:index.html.twig', array('horses' => $horses, 'form'=>$form->createView()));
    }

    public function horseShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$id, 'horse', $session->get('access_token'));
        $images = $this->get('image.handler.model')->getImagesThumb(array('horse' => $id,'access_token' => $session->get('access_token')));
        $history = $this->get('history.handler.model')->getHistories($horse, $session->get('access_token'));

        return $this->render('BackendBundle:Horse:view.html.twig', array('horse' => $horse, 'images' => $images, 'history' => $history));
    }

    public function newHorseAction()
    {
        $form = $this->createForm(new HorseType());

        return $this->render('BackendBundle:Horse:create.html.twig', array('form' => $form->createView()));
    }

    public function editHorseAction($id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->get('horses/'.$id, 'horse', $session->get('access_token'));
        $form = $form = $this->createForm(new HorseType(), $horse);

        return $this->render('BackendBundle:Horse:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createHorseAction()
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('horse');
        $horse = $this->get('rest.handler.model')->post("horses", array('horse'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('horses_list'));
    }

    public function putHorseAction($id)
    {
        $params = $this->getRequest()->request->get('horse');
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->put("horses/".$id, array('horse'=>$params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('horses_list'));
    }

    public function deleteHorseAction($id)
    {
        $session = $this->getRequest()->getSession();
        $horse = $this->get('rest.handler.model')->delete("horses/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('horses_list'));
    }
}