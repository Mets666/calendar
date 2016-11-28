<?php


namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EventCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                    'label' => 'Category name:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('color', TextType::class, array(
                    'label' => 'Color:',
                    'attr' => array('class' => 'form-control jscolor')
                )
            )
            ->add('add', SubmitType::class, array(
                'label' => 'Add',
                'attr' => array('class' => 'btn btn-default btn-success')
            ));
    }
}