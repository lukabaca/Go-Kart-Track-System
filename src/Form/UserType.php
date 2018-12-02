<?php
namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            'required' => TRUE,
            'options' => array('attr' => array('class' => 'form-control')),
            'first_options'  => array('label' => 'Hasło'),
            'second_options' => array('label' => 'Powtórz hasło'),
        ));

        $builder->add('name', TextType::class, array(
            'label' => 'Imię',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Imię',
                'class' => 'form-control'
            ]
        ));

        $builder->add('surname', TextType::class, array(
            'label' => 'Nazwisko',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Nazwisko',
                'class' => 'form-control'
            ]
        ));

        $builder->add('birthDate', BirthdayType::class, array(
            'label' => 'Data urodzenia',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Data urodzenia',
                'class' => 'form-control'
            ],
            'format' => 'yyyy-MM-dd',
            'widget' => 'choice',
        ));

        $builder->add('pesel', TextType::class, array(
            'label' => 'Pesel',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Pesel',
                'class' => 'form-control'
            ]
        ));

        $builder->add('documentID', TextType::class, array(
            'label' => 'Numer i seria dowodu osobistego',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Numer i seria dowodu osobistego',
                'class' => 'form-control'
            ]
        ));

        $builder->add('telephoneNumber', TextType::class, array(
            'label' => 'Numer telefonu',
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Numer telefonu',
                'class' => 'form-control'
            ],
            'help' =>'Podaj numer komórkowy jako 9 cyfr bez spacji',
        ));

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Zarejestruj się',
            'attr' => ['id' => 'submitButton',
                'class' => 'btn btn-primary submitButton'
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