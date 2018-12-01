<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextareaType::class, array(
            'label' => FALSE,
            'required' => TRUE,
            'attr' => [
                'placeholder' => 'Opis',
                'class' => 'form-control'
            ]
        ));
        $builder->add('file', FileType::class, array(
            'label' => 'Plik',
            'required' => FALSE,
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