<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.08.18
 * Time: 09:53
 */

namespace App\Form;

use App\Entity\User;
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
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('email', EmailType::class, array(
            'label' => 'Email',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]


        ));

        $builder->add('password', RepeatedType::class, array(
            'type' => PasswordType::class,
            'invalid_message' => 'Hasła muszą się zgadzać',
            'label' => 'Hasło',
            'required' => FALSE,
            'options' => array('attr' => array('class' => 'form-control')),

            'first_options'  => array('label' => 'Password'),
            'second_options' => array('label' => 'Repeat Password'),
        ));

        $builder->add('name', TextType::class, array(
            'label' => 'Imię',
            'required' => false,
            'attr' => [
                'placeholder' => 'Imię',
                'class' => 'form-control'
            ]

        ));

        $builder->add('surname', TextType::class, array(

            'label' => 'Nazwisko',
            'required' => false,
            'attr' => [
                'placeholder' => 'Nazwisko',
                'class' => 'form-control'
            ]
        ));

        $builder->add('birthDate', BirthdayType::class, array(
            'label' => 'Data urodzenia',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Data urodzenia',
                'class' => 'form-control'
            ],
            'format' => 'yyyy-MM-dd',
        ));

        $builder->add('pesel', TextType::class, array(

            'label' => 'Pesel',
            'required' => false,
            'attr' => [
                'placeholder' => 'Pesel',
                'class' => 'form-control'
            ]
        ));

        $builder->add('documentID', TextType::class, array(
            'label' => 'Numer i seria dowodu osobistego',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Numer i seria dowodu osobistego',
                'class' => 'form-control'
            ]


        ));

        $builder->add('telephoneNumber', TextType::class, array(

            'label' => 'Numer telefonu',
            'required' => false,
            'attr' => [
                'placeholder' => 'Numer telefonu',
                'class' => 'form-control'
            ]
        ));

        $builder->add('submit', SubmitType::class, array(

            'label' => 'Zarejestruj się',
            'attr' => ['id' => 'submitButton',
                'class' => 'btn btn-info submitButton'
            ]
        ));


    }


    public  function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'submit_label' => 'Wyslij zgloszenie',
                'data_class' => 'App\Entity\User',
                'validation_groups' => ['Default'],

            ]
        );
    }


}