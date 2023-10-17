<?php

namespace App\Form;

use App\Entity\Activite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;


class ActiviteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomActivite',TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom Activité',
                ]
            ])
            ->add('dureeActivite',TextType::class, [
                'attr' => [
                    'placeholder' => 'Durée Activité',
                ]
            ])
            ->add('tenueActivite',TextType::class, [
                'attr' => [
                    'placeholder' => 'Tenue Recommandation',
                ]
            ])
            ->add('difficulteActivite', ChoiceType::class, [
                'choices' => [
                    'Facile' => 'Facile',
                    'Moyenne' => 'Moyenne',
                    'Difficile' => 'Difficile',
                ],
                'placeholder' => 'Veuillez choisir la difficulté',
            ])
            ->add('imageActivite',FileType::class,[
                'label' => 'Choisir une photo',
                'data_class' => null,
            ])

            ->add('descriptionActivite',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Description Activité',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
