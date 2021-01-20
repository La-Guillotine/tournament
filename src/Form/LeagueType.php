<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\League;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LeagueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la ligue',
                'required' => true
            ])
            ->add('website', TextType::class, [
                'label' => 'Site web',
                'required' => false
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => true
            ])
            ->add('responsible', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'fullName'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => League::class,
        ]);
    }
}
