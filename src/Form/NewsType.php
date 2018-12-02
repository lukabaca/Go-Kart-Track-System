<?php
namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description', TextareaType::class, array(
            'label' => FALSE,
            'required' => FALSE,
            'attr' => [
                'placeholder' => 'Opis',
                'class' => 'form-control'
            ],
            'constraints' => new Callback(array($this, 'validate')),
        ));
        $builder->add('file', FileType::class, array(
            'label' => 'Plik',
            'required' => FALSE,
            'constraints' => new Callback(array($this, 'validate')),
        ));

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Zapisz',
            'attr' => ['id' => 'addTrackInfoButton',
                'class' => 'btn btn-primary submitButton'
            ],

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

    public function validate($value, ExecutionContextInterface $context)
    {
        /** @var \Symfony\Component\Form\Form $form */
        $form = $context->getRoot();
        $data = $form->getData();
        $description = $data->getDescription();
        $file = $data->getFile();
        if (!$description && !$file) {
            $context->buildViolation('Przynajmniej jedno z pól jest wymagane by dodać aktualność')
                ->addViolation();
        }
    }
}