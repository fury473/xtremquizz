<?php
namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProcessQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $question = $options['data']['question'];
        
        $builder->add('answers', 'entity', array(
        'class' => 'MetinetXtremQUIZZBundle:Answer',
        'label' => ' ',
        'attr' => array('class' => 'answers answersToQuestion_'.$question->getId().' reponse'),
        'expanded' => 'true',
        'query_builder' => function(EntityRepository $er) use ($question) {
            return $er->createQueryBuilder('a')
                ->where('a.question = '.$question->getId())
                ;
        },
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_processquestiontype';
    }
}
?>