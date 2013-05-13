<?php

namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fbUid')
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('picture')
            ->add('points')
            ->add('averageTime')
            ->add('nbQuizz')
            ->add('createdAt')
            ->add('lastconnectAt')
            ->add('answers')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_usertype';
    }
}
