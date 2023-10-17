<?php

namespace App\Form;

use App\Entity\Menu;
use App\Entity\Plat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('description', TextareaType::class, [
            'attr' => ['rows' => 5, 'cols' => 20],
        ])
            ->add('image', FileType::class, [
                'data_class' => null,
            ])
            ->add('calories')
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Disponible' => 1,
                    'Non-Disponible' => 0,
                ],
            ])

            ->add('categories')
         //   ->add('UserId')
            ->add('nbp')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
