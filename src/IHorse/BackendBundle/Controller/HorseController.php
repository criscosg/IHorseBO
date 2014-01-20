<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HorseType;

class HorseController extends IHorseController
{
    public function listHorsesAction()
    {
        $horses = $this->get('horse.handler.model')->getHorses();

        return $this->render('BackendBundle:Horse:index.html.twig', array('horses' => $horses));
    }

    public function horseShowAction($id)
    {
        $horse = $this->get('horse.handler.model')->getHorse($id);
        $images = $this->get('image.handler.model')->getImagesThumb(array('horse'=>$id));

        return $this->render('BackendBundle:Horse:view.html.twig', array('horse' => $horse, 'images'=>$images));
    }

    public function newHorseAction()
    {
        $form = $form = $this->createForm(new HorseType());

        return $this->render('BackendBundle:Horse:create.html.twig', array('form' => $form->createView()));
    }

    public function editHorseAction($id)
    {
        $horse = $this->get('horse.handler.model')->getHorse($id);
        $form = $form = $this->createForm(new HorseType(), $horse);

        return $this->render('BackendBundle:Horse:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createHorseAction()
    {
        $params = $this->getRequest()->request->get('horse');
        $horse = $this->get('horse.handler.model')->postHorse(array('horse' => $params));

        return $this->redirect($this->generateUrl('horses_list'));
    }

    public function putHorseAction($id)
    {
        $params = $this->getRequest()->request->get('horse');

        $horse = $this->get('horse.handler.model')->putHorse($id, array('horse' => $params));

        return $this->redirect($this->generateUrl('horses_list'));
    }

    public function deleteHorseAction($id)
    {
        $horse = $this->get('horse.handler.model')->deleteHorse($id);

        return $this->redirect($this->generateUrl('horses_list'));
    }
}