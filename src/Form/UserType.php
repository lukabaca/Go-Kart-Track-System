<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.08.18
 * Time: 09:53
 */

namespace AppBundle\Types;


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


        $builder->add('login', TextType::class, array(
            'label' => 'Login',
            'required' => TRUE,
            'attr' => ['placeholder' => 'Login']


        ));

        $builder->add('password', PasswordType::class, array(

            'label' => 'Hasło',
            'required' => TRUE,
            'attr' => ['placeholder' => 'Hasło']
        ));

        $builder->add('submit', SubmitType::class, array(

            'label' => 'Zaloguj się',
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
                'data_class' => 'AppBundle\Entity\User',
                'validation_groups' => ['Default'],

            ]
        );
    }


}