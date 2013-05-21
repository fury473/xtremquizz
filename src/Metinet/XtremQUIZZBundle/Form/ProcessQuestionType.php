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
        $question = $options['data'];
        echo $question->getTitle();
        $builder->add('answers', 'entity', array(
        'class' => 'MetinetXtremQUIZZBundle:Answer',
        'label' => $question->getTitle(),
        'attr' => array('class' => 'answers answersToQuestion_'.$question->getId()),
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
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Question'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_processquestiontype';
    }
}
?>