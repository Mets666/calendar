<?php


namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->user = $options['user'];

        $builder
            ->add('filter_category', EntityType::class, array(
                    'class' => 'AppBundle:EventCategory',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                            ->where('category.user = :userId')
                            ->setParameter('userId', $this->user->getId())
                            ->orderBy('category.title', 'ASC');
                    },
                    'choice_label' => 'title',
                    'required' => false,
                    'placeholder' => 'All categories',
                    'empty_data' => null,
                    'label' => 'Filter by category:',
                    'attr' => array('class' => 'form-control input-sm', 'id' => 'category_filter_selector')
                )
            )
            ->add('filter_project', EntityType::class, array(
                'class' => 'AppBundle:Project',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('project')
                        ->where('project.user = :userId')
                        ->setParameter('userId', $this->user->getId())
                        ->orderBy('project.title', 'ASC');
                },
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'All projects',
                'empty_data' => null,
                'label' => 'Filter by project:',
                'attr' => array('class' => 'form-control input-sm', 'id' => 'project_filter_selector')
            )
    );
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