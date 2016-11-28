<?php


namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * @property \AppBundle\Entity\User user
 */
class CalendarEventType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->user = $options['user'];

        $builder
            ->add('title', TextType::class, array(
                    'label' => 'Event name:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('startDate', DateTimeType::class, array(
                    'label' => 'Start:',
                    'attr' => array('class' => '')
                )
            )
            ->add('endDate', DateTimeType::class, array(
                    'label' => 'End:',
                    'attr' => array('class' => '')
                )
            )
            ->add('category', EntityType::class, array(
                    'class' => 'AppBundle:EventCategory',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                            ->where('category.user = :userId')
                            ->setParameter('userId', $this->user->getId())
                            ->orderBy('category.title', 'ASC');
                    },
                    'choice_label' => 'title',
                    'required' => false,
                    'placeholder' => 'None',
                    'empty_data' => null,
                    'label' => 'Category:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('add', SubmitType::class, array(
                'label' => 'Add',
                'attr' => array('class' => 'btn btn-default btn-success')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
        ));

    }
}