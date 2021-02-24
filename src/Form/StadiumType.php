<?php

namespace App\Form;

use App\Entity\Stadium;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class StadiumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "label" => "Nom du stade",
                "required" => true
            ])
            ->add('picture', DropzoneType::class, [
                'label' => 'Image du stade',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/svg'],
                        'mimeTypesMessage' => 'Veuillez renseigner une image au format (.jpg, .jpeg, .png ou .svg)'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                "label" => "Adresse",
                "required" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stadium::class,
        ]);
    }
}
