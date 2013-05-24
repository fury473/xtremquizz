<?php

namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array('label'=>'Titre'))
            ->add('picture','file',array('label'=>'Image',
                                'data_class' => null,
                                'required' => false,
                                'property_path' => 'picture'))
            ->add('quizz','entity', array(
                            'class' => 'Metinet\XtremQUIZZBundle\Entity\Quizz',
                            'attr' => array('class'=>'hidden'),
                            'label' => ' '
                            ));
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Question'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_questiontype';
    }
}
