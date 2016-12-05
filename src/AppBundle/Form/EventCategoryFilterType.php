<?php


namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventCategoryFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->user = $options['user'];

        $builder
            ->add('category', EntityType::class, array(
                    'class' => 'AppBundle:EventCategory',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                            ->where('category.user = :userId')
                            ->setParameter('userId', $this->user->getId())
                            ->orderBy('category.title', 'ASC');
                    },
                    'choice_label' => 'title',
                    'choice_value' => 'title',
                    'required' => false,
                    'placeholder' => 'All',
                    'empty_data' => null,
                    'label' => 'Filter by category:',
                    'attr' => array('class' => 'form-control', 'id' => 'filter_selector')
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'user' => null,
        ));

    }
}