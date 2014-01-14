<?php
namespace IHorse\BackendBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use IHorse\BackendBundle\Model\Handler\ClinicHandler;

class JsonClinicType extends AbstractType
{
    private $handler;

    public function __construct(ClinicHandler $handler)
    {
        $this->handler = $handler;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'choices' => $this->getChoices(),
        ));
    }

    private function getChoices()
    {
        $clinics = $this->handler->getList('clinics', 'clinics');
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