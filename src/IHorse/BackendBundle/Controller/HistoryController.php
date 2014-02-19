<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HistoryType;

class HistoryController extends IHorseController
{
    public function listHistoriesAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $histories = $this->get('rest.handler.model')->getList('horses/'.$horse.'/logs', null, $session->get('access_token'));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));

        return $this->render('BackendBundle:History:index.html.twig', array('histories' => $histories, 'horse' => $horse));
    }

    public function historyShowAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $history = $this->get('rest.handler.model')->get('horses/'.$horse.'/logs/'.$id, null, $session->get('access_token'));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));

        return $this->render('BackendBundle:History:view.html.twig', array('history' => $history, 'horse' => $horse));
    }

    public function newHistoryAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $form = $form = $this->createForm(new HistoryType());
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));

        return $this->render('BackendBundle:History:create.html.twig', array('form' => $form->createView(), 'horse' => $horse));
    }

    public function editHistoryAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $history = $this->get('rest.handler.model')->get('horses/'.$horse.'/logs/'.$id, null, $session->get('access_token'));
        $horse = $this->get('rest.handler.model')->get('horses/'.$horse, 'horse', $session->get('access_token'));
        $form = $form = $this->createForm(new HistoryType(), $history);

        return $this->render('BackendBundle:History:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id, 'horse' => $horse));
    }

    public function createHistoryAction($horse)
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('history');
        $history = $this->get('rest.handler.model')->post('horses/'.$horse.'/logs', array('log' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }

    public function putHistoryAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $params = $this->getRequest()->request->get('history');
        $history = $this->get('rest.handler.model')->put('horses/'.$horse.'/logs/'.$id, array('log' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }

    public function deleteHistoryAction($horse, $id)
    {
        $session = $this->getRequest()->getSession();
        $history = $this->get('rest.handler.model')->delete('horses/'.$horse.'/logs/'.$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('histories_list', array('horse' => $horse)));
    }
}