<?php

namespace App\Form;
use App\Entity\KartTechnicalData;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KartType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
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

        $builder->add('prize', IntegerType::class, array(
            'label' => 'Cena za przejazd',
            'required' => TRUE,
            'invalid_message' => 'Proszę wprowadzić liczbę',
            'help' => 'Cena jest wyrażona w zł',
            'attr' => [
                'placeholder' => 'Cena za przejazd',
                'class' => 'form-control',
                'min' => 1,
            ],
        ));

        $builder->add('availability', CheckboxType::class, array(
            'label' => 'Dostępność',
            'required' => FALSE,
        ));

        $builder->add('description', TextareaType::class, array(
            'label' => 'Opis',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Opis',
                'class' => 'form-control'
            ],
        ));

        $builder->add('kartTechnicalData', KartTechnicalDataType::class);

        $builder->add('file', FileType::class, array(
            'label' => 'Plik',
            'required' => FALSE,
        ));

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Zapisz',
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