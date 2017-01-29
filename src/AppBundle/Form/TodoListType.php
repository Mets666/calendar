<?php
/**
 * Created by PhpStorm.
 * User: Matej Sadloň
 * Date: 29.1.2017
 * Time: 14:11
 */

namespace AppBundle\Form;


use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class TodoListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, ['mapped'=>false])
            ->add('title', TextType::class, array(
                    'label' => 'Category name:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('description', TextType::class, array(
                    'label' => 'Category name:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('color', TextType::class, array(
                    'label' => 'Color:',
                    'attr' => array('class' => 'form-control jscolor',
                        'style' => 'text-indent: -9999px;',
                        'readonly' => 'readonly'
                    )
                )
            )
            ->add('add', SubmitType::class, array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-default btn-success')
            ));
    }
}