<?php

namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\VeterinaryType;

class VeterinaryController extends IHorseController
{
    public function listVeterinariesAction()
    {
        $session = $this->getRequest()->getSession();
        $veterinaries = $this->get('rest.handler.model')->getList('veterinaries', 'veterinaries', $session->get('access_token'));

        return $this->render('BackendBundle:Veterinary:index.html.twig', array('veterinaries' => $veterinaries));
    }

    public function veterinaryShowAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->get('veterinaries/'.$id, 'veterinary', $session->get('access_token'));

        return $this->render('BackendBundle:Veterinary:view.html.twig', array('veterinary' => $veterinary));
    }

    public function newVeterinaryAction()
    {
        $form = $form = $this->createForm(new VeterinaryType());

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView()));
    }

    public function editVeterinaryAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->get('veterinaries/'.$id, 'veterinary', $session->get('access_token'));
        $form = $form = $this->createForm(new VeterinaryType(), $veterinary);

        return $this->render('BackendBundle:Veterinary:create.html.twig', array('form' => $form->createView(),'edition' => true, 'id' => $id));
    }

    public function createVeterinaryAction()
    {
        $params = $this->getRequest()->request->get('veterinary');
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->post("veterinaries", array('veterinary' => $params), $session->get('access_token'));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function putVeterinaryAction($id)
    {
        $params = $this->getRequest()->request->get('veterinary');
        $session = $this->getRequest()->getSession();
        ldd($params);
        if ($params['password'] == "") {
            $veterinary = $this->get('rest.handler.model')->patch("veterinaries/".$id, array('veterinary' => $params), $session->get('access_token'));
        } else {
            $veterinary = $this->get('rest.handler.model')->put("veterinaries/".$id, array('veterinary' => $params), $session->get('access_token'));
        }

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }

    public function deleteVeterinaryAction($id)
    {
        $session = $this->getRequest()->getSession();
        $veterinary = $this->get('rest.handler.model')->delete("veterinaries/".$id, $session->get('access_token'));

        return $this->redirect($this->generateUrl('veterinaries_list'));
    }
}