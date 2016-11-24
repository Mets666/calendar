<?php


namespace AppBundle\Form;


use AppBundle\Form\Type\ColorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TimeLogCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('color', TextType::class, array(
                    'attr' => array('class' => 'form-control jscolor')

                )
            );
    }
}