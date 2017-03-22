<?php


namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class ProjectType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('id', HiddenType::class, ['mapped'=>false])
            ->add('title', TextType::class, array(
                    'label' => 'Project title:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('acronym', TextType::class, array(
                    'label' => 'Acronym:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('timeLimit', IntegerType::class, array(
                    'label' => 'Time assigned to project:',
                    'attr' => array('class' => 'form-control', 'min' => 1, 'max' => 1000)
                )
            )
            ->add('description', TextareaType::class, array(
                    'label' => 'Description:',
                    'attr' => array('class' => 'form-control vertical-resize', 'rows' => '4')
                )
            )
            ->add('add', SubmitType::class, array(
                    'label' => 'Submit',
                    'attr' => array('class' => 'btn btn-default btn-success width80px')
                )
            );
    }
}