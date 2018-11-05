<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-02
 * Time: 23:55
 */

namespace App\Form;


use App\Entity\KartTechnicalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KartTechnicalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('power', TextType::class, array(
            'label' => 'Moc',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Moc',
                'class' => 'form-control'
            ]
        ));
        $builder->add('vmax', TextType::class, array(

            'label' => 'Prędkość maksymalna',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Prędkość maksymalna',
                'class' => 'form-control'
            ]
        ));
        $builder->add('engine', TextType::class, array(

            'label' => 'Silnik',
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Silnik',
                'class' => 'form-control'
            ]
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\KartTechnicalData',
            'validation_groups' => ['Default'],
        ));
    }
}