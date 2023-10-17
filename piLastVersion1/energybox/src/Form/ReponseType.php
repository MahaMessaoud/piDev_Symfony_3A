<?php

namespace App\Form;

use App\Entity\Reponse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('objetReponse',TextType::class, [
                'attr' => [
                    'placeholder' => 'Objet',
                ]
            ])
            ->add('pieceJointe',FileType::class,[
                'label' => 'Choisir une piece jointe',
                'data_class' => null,
            ])
            ->add('contenuReponse',TextAreaType::class, [
                'attr' => [
                    'placeholder' => 'Contenu Réponse',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}
