<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-02
 * Time: 18:09
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(

            'label' => 'Nazwa gokartu',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Nazwa gokartu',
                'class' => 'form-control'
            ]
        ));

        $builder->add('prize', TextType::class, array(
            'label' => 'Cena za przejazd',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Cena za przejazd',
                'class' => 'form-control'
            ],
        ));


        $builder->add('submit', SubmitType::class, array(

            'label' => 'Dodaj',
            'attr' => ['id' => 'addKartButton',
                'class' => 'btn btn-primary submitButton'
            ]
        ));


    }


    public  function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'submit_label' => 'Wyslij zgloszenie',
                'data_class' => 'App\Entity\Kart',
                'validation_groups' => ['Default'],

            ]
        );
    }
}