<?php

namespace App\Form;

use App\Entity\CategoryReclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class CategoryReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCategory',TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom Catégorie',
                ]
            ])
            ->add('descriptionCategory',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Description Catégorie',
                ]
            ])
            ->add('prioriteCategory', ChoiceType::class, [
                'choices' => [
                    'Faible' => 'Faible',
                    'Moyenne' => 'Moyenne',
                    'Haute' => 'Haute',
                ],
                'placeholder' => 'Veuillez choisir la priorité de la catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategoryReclamation::class,
        ]);
    }
}
