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
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('email', TextType::class, array(
            'label' => 'Email',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]


        ));

        $builder->add('password', PasswordType::class, array(

            'label' => 'Hasło',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('name', TextType::class, array(
            'label' => 'Email',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]

        ));

        $builder->add('surname', PasswordType::class, array(

            'label' => 'Nazwisko',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('birthDate', TextType::class, array(
            'label' => 'Data urodzenia',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('pesel', PasswordType::class, array(

            'label' => 'Pesel',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('documentID', TextType::class, array(
            'label' => 'Numer i seria dowodu osobistego',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]


        ));

        $builder->add('telephoneNumber', PasswordType::class, array(

            'label' => 'Numer telefonu',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('submit', SubmitType::class, array(

            'label' => 'Zarejestruj się',
            'attr' => ['id' => 'submitButton',
                'class' => 'btn btn-main submitButton'
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