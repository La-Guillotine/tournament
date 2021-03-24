<?php

namespace App\Form;

use DateTime;
use App\Entity\Stadium;
use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre'
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description',
                'required' => false
            ])
            ->add('start_date', DateType::class,[
                'label' => 'Date de début',
                'years' => range(date('Y'), date('Y')+20),
                'required' => true
            ])
            ->add('end_date', DateType::class,[
                'label' => 'Date de Fin',
                'years' => range(date('Y'), date('Y')+20),
                'required' => true
            ])
            ->add('nbMaxTeam', NumberType::class,[
                'attr' => [
                    "min" => 2,
                    "max" => 64
                ],
                "html5" => true,
                "scale" => 0,
                'label' => 'Nombre maximum d\'équipes autorisées'
            ])
            ->add('ageCategory', ChoiceType::class,[
                'label' => 'Catégorie d\'Age',
                'choices' => [
                    'Poussins' => 'poussins',
                    'Benjamins' => 'benjamains',
                    'Cadets' => 'cadets',
                    'Juniors' => 'juniors',
                    'Seniors' => 'seniors',
                    'Vétérans' => 'vétérans'
                ],
                'required' => false
            ])
            ->add('stadium', EntityType::class, [
                'class' => Stadium::class,
                'choice_label' => 'name',
                'label' => 'Stade'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
