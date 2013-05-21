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
            ->add('title','text',array('label'=>'Titre'))
            ->add('picture','text',array('label'=>'Image'))
            ->add('shortDesc','text',array('label'=>'Courte description'))
            ->add('longDesc','text',array('label'=>'Longue description'))
            ->add('winPoints','integer',array('label'=>'Points à gagner'))
            ->add('averageTime','integer',array('label'=>'Temps moyen'))
            ->add('txtWin1','text',array('label'=>'Premier résultat'))
            ->add('txtWin2','text',array('label'=>'Deuxième résultat'))
            ->add('txtWin3','text',array('label'=>'Troisième résultat'))
            ->add('txtWin4','text',array('label'=>'Quatrième résultat'))
            ->add('shareWallTitle','text',array('label'=>'Titre du partage'))
            ->add('shareWallDesc','text',array('label'=>'Description du partage'))
            ->add('isPromoted','checkbox',array('label' => 'Publier'))
            ->add('state','integer',array('label'=>'Etat'))
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
