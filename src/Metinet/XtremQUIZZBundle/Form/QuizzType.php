<?php

namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('picture')
            ->add('shortDesc')
            ->add('longDesc')
            ->add('winPoints')
            ->add('averageTime')
            ->add('txtWin1')
            ->add('txtWin2')
            ->add('txtWin3')
            ->add('txtWin4')
            ->add('shareWallTitle')
            ->add('shareWallDesc')
            ->add('isPromoted')
            ->add('createdAt')
            ->add('state')
            ->add('theme')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Quizz'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_quizztype';
    }
}
