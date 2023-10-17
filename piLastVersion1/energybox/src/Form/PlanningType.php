<?php

namespace App\Form;

use App\Entity\Planning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use DateTime;


class PlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('datePlanning', DateType::class, [
                'widget' => 'single_text',
                'html5'=>true,
                'attr'=>['min'=>(new DateTime())->format('Y-m-d')],
            ])
            ->add('jourPlanning', ChoiceType::class, [
                'choices' => [
                    'Lundi' => 'Lundi',
                    'Mardi' => 'Mardi',
                    'Mercredi' => 'Mercredi',
                    'Jeudi' => 'Jeudi',
                    'Vendredi' => 'Vendredi',
                    'Samedi' => 'Samedi',
                    'Dimanche' => 'Dimanche',
                ],
                'placeholder' => 'Veuillez choisir le jour.',
            ])
            ->add('heurePlanning', ChoiceType::class, [
                'choices' => [
                    '06 AM' => '6',
                    '09 AM' => '9',
                    '11 AM' => '11',
                    '01 PM' => '13',
                    '04 PM' => '16',
                    '07 PM' => '19',
                ],
                'placeholder' => 'Veuillez choisir l heure de début du cours.',
            ])
            ->add('cours', EntityType::class, [
                'class' => 'App\Entity\Cours',
                'choice_label' => 'nomCours',
                'placeholder' => 'Veuillez choisir le cours.'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planning::class,
        ]);
    }
}
