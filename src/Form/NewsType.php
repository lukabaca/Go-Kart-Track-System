<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-19
 * Time: 13:37
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextType::class, array(
            'label' => 'Ulica i adres',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Ulica i adres',
                'class' => 'form-control'
            ]
        ));

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Zapisz',
            'attr' => ['id' => 'addTrackInfoButton',
                'class' => 'btn btn-primary submitButton'
            ]
        ));
    }

    public  function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'submit_label' => 'Dodaj aktualność',
                'data_class' => 'App\Entity\News',
                'validation_groups' => ['Default'],
            ]
        );
    }
}