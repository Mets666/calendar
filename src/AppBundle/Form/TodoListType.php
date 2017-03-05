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
            ->add('items', CollectionType::class, array(
                    // each entry in the array will be an "email" field
                    'entry_type'   => ListItemType::class,
                    // these options are passed to each "email" type
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TodoList::class,
        ));
    }
}