<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrackInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('street', TextType::class, array(
            'label' => 'Ulica i adres',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Ulica i adres',
                'class' => 'form-control'
            ]
        ));

        $builder->add('city', TextType::class, array(
            'label' => 'Miasto',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Miasto',
                'class' => 'form-control'
            ]
        ));

        $builder->add('email', EmailType::class, array(
            'label' => 'Email',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Email',
                'class' => 'form-control'
            ]
        ));

        $builder->add('telephone_number', TextType::class, array(
            'label' => 'Numer kontaktowy',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Numer kontaktowy',
                'class' => 'form-control'
            ],
            'help' => 'Proponowany format to xxx-yyy-zzz',
        ));

        $builder->add('hourStart', TimeType::class, array(
            'label' => 'Godzina otwarcia',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Godzina otwarcia',
                'class' => 'form-control'
            ]
        ));

        $builder->add('hourEnd', TimeType::class, array(
            'label' => 'Godzina zamknięcia',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Godzina zamknięcia',
                'class' => 'form-control'
            ]
        ));

        $builder->add('facebookLink', TextType::class, array(
            'label' => 'Link do facebooka',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Link do facebooka',
                'class' => 'form-control'
            ]
        ));

        $builder->add('instagramLink', TextType::class, array(
            'label' => 'Link do instagrama',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Link do instagrama',
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
                'submit_label' => 'Wyslij zgloszenie',
                'data_class' => 'App\Entity\trackConfig\TrackInfo',
                'validation_groups' => ['Default'],
            ]
        );
    }
}