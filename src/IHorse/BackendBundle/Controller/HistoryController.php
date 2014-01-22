<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HistoryType;

class HistoryController extends IHorseController
{
    public function listHistoriesAction($horse)
    {
        $histories = $this->get('history.handler.model')->getHistories($horse);
        $horse = $this->get('horse.handler.model')->getHorse($horse);

        return $this->render('BackendBundle:History:index.html.twig', array('histories' => $histories, 'horse' => $horse));
    }

    public function historyShowAction($horse, $id)
    {
        $history = $this->get('history.handler.model')->getHistory($horse, $id);
        $horse = $this->get('horse.handler.model')->getHorse($horse);

        return $this->render('BackendBundle:History:view.html.twig', array('history' => $history, 'horse' => $horse));
    }

    public function newHistoryAction($horse)
    {
        $form = $form = $this->createForm(new HistoryType());

        return $this->render('BackendBundle:History:create.html.twig', array('form' => $form->createView(), 'horse' => $horse));
    }

    public function editHistoryAction($horse, $id)
    {
        $history = $this->get('history.handler.model')->getHistory($horse, $id);
        $form = $form = $this->createForm(new HistoryType(), $history);
        $horse = $this->get('horse.handler.model')->getHorse($horse);

        return $this->render('BackendBundle:History:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id, 'horse' => $horse));
    }

    public function createHistoryAction($horse)
    {
        $params = $this->getRequest()->request->get('history');
        $history = $this->get('history.handler.model')->postHistory($horse, array('history' => $params));
        $horse = $this->get('horse.handler.model')->getHorse($horse);

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }

    public function putHistoryAction($horse, $id)
    {
        $params = $this->getRequest()->request->get('history');

        $history = $this->get('history.handler.model')->putHistory($horse, $id, array('history' => $params));
        $horse = $this->get('horse.handler.model')->getHorse($horse);

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }

    public function deleteHistoryAction($horse, $id)
    {
        $history = $this->get('history.handler.model')->deleteHistory($horse, $id);

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }
}