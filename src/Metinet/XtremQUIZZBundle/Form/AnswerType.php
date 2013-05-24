<?php

namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=>'Titre'))
            ->add('isCorrect', 'checkbox', array(
                'label'     => 'Bonne Réponse',
                'required'  => false
            ))
            ->add('question','entity', array(
                            'class' => 'Metinet\XtremQUIZZBundle\Entity\Question',
                            'attr' => array('class'=>'hidden'),
                            'label' => ' '
                            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Answer'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_answertype';
    }
}
