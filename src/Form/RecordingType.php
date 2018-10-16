<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.08.18
 * Time: 09:53
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
class RecordingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(

            'label' => 'Tytuł nagrania',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Tytuł nagrania',
                'class' => 'form-control'
            ]
        ));

        $builder->add('recordingLink', TextType::class, array(
            'label' => 'Link',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Link',
                'class' => 'form-control'
            ],
        ));


        $builder->add('submit', SubmitType::class, array(

            'label' => 'Dodaj',
            'attr' => ['id' => 'addRecordingButton',
                'class' => 'btn btn-primary submitButton'
            ]
        ));


    }


    public  function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'submit_label' => 'Wyslij zgloszenie',
                'data_class' => 'App\Entity\Recording',
                'validation_groups' => ['Default'],

            ]
        );
    }


}