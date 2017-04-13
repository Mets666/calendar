<?php


namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;


class CalendarEventType extends AbstractType
{

    private $user;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->user = $options['user'];

        $builder
            ->setMethod('POST')
            ->add('id', HiddenType::class, ['mapped'=>false])
            ->add('title', TextType::class, array(
                    'label' => 'Title:',
                    'attr' => array('class' => 'form-control input-sm')
                )
            )
            ->add('note', TextareaType::class, array(
                    'label' => 'Note:',
                    'attr' => array('class' => 'form-control input-sm'),
                    'required' => false
                )
            )
            ->add('startDate', TextType::class, array(
                    'label' => 'Start:',
                    'attr' => array(
                        'class' => 'form-control input-sm',
                    )
                )
            )
            ->add('endDate', TextType::class, array(
                    'label' => 'End:',
                    'attr' => array(
                        'class' => 'form-control input-sm',
                    )
                )
            )
            ->add('project', EntityType::class, array(
                    'class' => 'AppBundle:Project',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('project')
                            ->where('project.user = :userId')
                            ->setParameter('userId', $this->user->getId())
                            ->orderBy('project.title', 'ASC');
                    },
                    'choice_label' => 'title',
                    'choice_value' => 'id',
                    'required' => false,
                    'placeholder' => 'None',
                    'empty_data' => null,
                    'label' => 'Project:',
                    'attr' => array('class' => 'form-control input-sm')
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
                    'choice_value' => 'id',
                    'required' => false,
                    'placeholder' => 'None',
                    'empty_data' => null,
                    'label' => 'Category:',
                    'attr' => array('class' => 'form-control input-sm')
                )
            )
            ->add('add', SubmitType::class, array(
                    'label' => 'Add event',
                    'attr' => array('class' => 'btn btn-default btn-success btn-block')
            ));

        $builder->get('startDate')
            ->addModelTransformer(new DateTimeToStringTransformer(null, null, 'Y-m-d H:i'))
        ;

        $builder->get('endDate')
            ->addModelTransformer(new DateTimeToStringTransformer(null, null, 'Y-m-d H:i'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
        ));

    }
}