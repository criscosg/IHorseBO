<?php
namespace IHorse\BackendBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use IHorse\BackendBundle\Model\Handler\RESTHandler;

class JsonClinicType extends AbstractType
{
    private $handler;
    private $container;

    public function __construct(RESTHandler $handler, ContainerInterface $container)
    {
        $this->handler = $handler;
        $this->container = $container;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'choices' => $this->getChoices(),
        ));
    }

    private function getChoices()
    {
        $clinics = $this->handler->getList('clinics', 'clinics', $this->container->get('session')->get('access_token'));
        $final = array();
        $id=1;
        foreach($clinics as $clinic){
            foreach ($clinic as $key=>$value) {
                if ($key == 'id'){
                    $id=$value;
                }
                if ($key == 'name'){
                    $final[$id]=$value;
                }
            }
        }

        return $final;
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'json_clinic';
    }

}