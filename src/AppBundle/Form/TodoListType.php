<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 29.1.2017
 * Time: 14:11
 */

namespace AppBundle\Form;

use AppBundle\Entity\TodoList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoListType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, ['mapped'=>false])
            ->add('title', TextType::class, array(
                    'label' => 'List title:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('description', TextareaType::class, array(
                    'label' => 'Description:',
                    'required' => false,
                    'attr' => array('class' => 'form-control', 'rows' => '4')
                )
            )
            // collection of embed forms for list items
            ->add('items', CollectionType::class, array(
                    'entry_type'   => ListItemType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                )
            )
            ->add('add', SubmitType::class, array(
                    'label' => 'Submit',
                    'attr' => array('class' => 'btn btn-default btn-success width80px')
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TodoList::class,
        ));
    }
}