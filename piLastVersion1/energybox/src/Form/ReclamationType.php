<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUserReclamation',TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                ]
            ])
            ->add('emailUserReclamation',EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                ]
            ])
            ->add('objetReclamation',TextType::class, [
                'attr' => [
                    'placeholder' => 'Objet',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => 'App\Entity\CategoryReclamation',
                'choice_label' => 'nomCategory'
            ])
            ->add('texteReclamation',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Saisir votre Réclamation',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
