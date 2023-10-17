<?php

namespace App\Form;

use App\Entity\Competition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CompetitionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomCompetition')
            ->add('fraisCompetition')
            ->add('dateCompetition' , DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['min' => (new \DateTime())->format('Y-m-d')], // Ajoutez cette ligne
])           
            
            ->add('nbrMaxInscrit')
            ->add('etatCompetition')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Competition::class,
        ]);
    }
}
