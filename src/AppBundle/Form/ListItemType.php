<?php


namespace AppBundle\Form;


use AppBundle\Entity\ListItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('id', HiddenType::class, ['mapped'=>false])
            ->add('text', TextType::class, array(
                    'label' => 'List title:',
                    'attr' => array('class' => 'form-control')
                )
            )
            ->add('done', CheckboxType::class, array(
                    'attr' => array('class' => ''),
                    'required' => false,
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ListItem::class,
        ));
    }
}