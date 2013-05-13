<?php

namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'Titre'))
            ->add('picture', null, array('label' => 'Image'))
            ->add('shortDesc', null, array('label' => 'Petite description'))
            ->add('longDesc', null, array('label' => 'Grande description'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Theme'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_themetype';
    }
}
