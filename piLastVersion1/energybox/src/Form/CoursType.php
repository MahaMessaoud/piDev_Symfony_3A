<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCours',TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom Cours',
                ]
            ])
            ->add('nomCoach', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom Coach',
                ]
            ])

            ->add('ageMinCours',TextType::class, [
                'attr' => [
                    'placeholder' => 'Age Minimale',
                ]
            ])
            ->add('prixCours',TextType::class, [
                'attr' => [
                    'placeholder' => 'Prix Cours',
                ]
            ])
            ->add('descriptionCours',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Description Cours',
                ]
            ])
            ->add('activites')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
