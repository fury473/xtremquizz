<?php
namespace Metinet\XtremQUIZZBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProcessQuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $quizz = $options['data'];
        $questions = $quizz->getQuestions();
        foreach($questions as $question) {
            echo $question->getTitle();
            $builder->add('questions', 'entity', array(
            'class' => 'MetinetXtremQUIZZBundle:Answer',
            'label' => $question->getTitle(),
            'attr' => array('class' => 'question_'.$question->getId()),
            'expanded' => 'true',
            'query_builder' => function(EntityRepository $er) use ($question) {
                return $er->createQueryBuilder('a')
                    ->where('a.question = '.$question->getId())
                    ;
            },
            ));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Metinet\XtremQUIZZBundle\Entity\Quizz'
        ));
    }

    public function getName()
    {
        return 'metinet_xtremquizzbundle_processquizztype';
    }
}
?>
