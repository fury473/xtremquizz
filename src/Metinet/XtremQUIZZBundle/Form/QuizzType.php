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
            ->add('picture','file',array('label'=>'Image',
                                'data_class' => null,
                                'property_path' => 'picture'))
            ->add('shortDesc','text',array('label'=>'Courte description'))
            ->add('longDesc','textarea',array('label'=>'Longue description'))
            ->add('winPoints','integer',array('label'=>'Points à gagner'))
            ->add('averageTime','integer',array('label'=>'Temps moyen'))
            ->add('txtWin1','textarea',array('label'=>'Résultat (100%)'))
            ->add('txtWin3','textarea',array('label'=>'Résultat (75%)'))
            ->add('txtWin2','textarea',array('label'=>'Résultat (50%)'))
            ->add('txtWin4','textarea',array('label'=>'Résultat (25%)'))
            ->add('shareWallTitle','text',array('label'=>'Titre du partage'))
            ->add('shareWallDesc','text',array('label'=>'Description du partage'))
            ->add('isPromoted','checkbox',array('label' => 'Mettre en avant', 'required' => false))
            ->add('state','integer',array('label'=>'Etat (1 si actif)'))
            ->add('theme','entity',array('label'=>'Thème',
                                'class' => 'MetinetXtremQUIZZBundle:Theme'))
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
