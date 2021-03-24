<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\League;
use App\Entity\Stadium;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class ClubType extends AbstractType
{
    private $authorization;
    public function __construct(AuthorizationChecker $authorizationChecker)
    {
    $this->authorization = $authorizationChecker;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du club',
                'required' => true
            ])
            ->add('acronym', TextType::class, [
                'label' => 'Acronyme',
                'required' => true,
                'constraints' => [
                    new Length(['max' => 4])
                ]
            ])
            ->add('logo', FileType::class, [
                'label' => 'Logo du club',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/*'],
                        'mimeTypesMessage' => 'Veuillez renseigner une image au format (.jpg, .jpeg, .png ou .svg)'
                    ])
                ]
            ])
            ->add('website', TextType::class, [
                'label' => 'Site du club',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => true
            ])
            ->add('stadium', EntityType::class, [
                'class' => Stadium::class,
                'choice_label' => 'name',
                'label' => 'Stade'
            ])
            ->add('league',EntityType::class, [
                'class' => League::class,
                'label' => "Ligue",
                'choice_label' => 'name',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
            'users' => []
        ]);
    }
}
