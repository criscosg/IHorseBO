<?php
namespace IHorse\BackendBundle\Controller;

use IHorse\BackendBundle\Controller\IHorseController;
use IHorse\BackendBundle\Form\HorseType;
use IHorse\BackendBundle\Form\ImageType;

class ImageController extends IHorseController
{
    public function newImageAction($horseId)
    {
        $horse = $this->get('horse.handler.model')->getHorse($horseId);
        $form = $form = $this->createForm(new ImageType());

        return $this->render('BackendBundle:Image:create.html.twig', array('form' => $form->createView(), 'horse'=>$horse));
    }

    public function createImageAction()
    {
        $file=$this->getRequest()->files->get('image');
        $files['image[file]']="@".$file['file']->getPathname();
        $params = $this->getRequest()->request->all();
        $image = $this->get('image.handler.model')->postImage($params, $files);

        return $this->redirect($this->generateUrl('show_horse', array('id'=>$params['image']['horse'])));
    }

    public function deleteImageAction($id)
    {
        $image = $this->get('image.handler.model')->deleteImage($id);

        return $this->redirect($this->generateUrl('show_horse', array('id'=>$params['horse'])));
    }
}