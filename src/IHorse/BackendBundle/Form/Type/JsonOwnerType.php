<?php
namespace IHorse\BackendBundle\Form\Type;

use IHorse\BackendBundle\Model\Handler\OwnerHandler;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;

class JsonOwnerType extends AbstractType
{
    private $handler;

    public function __construct(OwnerHandler $handler)
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
        $owners = $this->handler->getList('owners', 'owners');
        $final = array();
        $id = 1;
        foreach ($owners as $owner) {
            foreach ($owner as $key => $value) {
                if ($key == 'id') {
                    $id = $value;
                }
                if ($key == 'name'){
                    $final[$id] = $value;
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
        return 'json_owner';
    }

}