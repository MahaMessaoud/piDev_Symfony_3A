<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class AbonnementModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAchat', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
               ], // Ajoutez cette ligne
            ) 
            ->add('dateFin' , DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
               ], // Ajoutez cette ligne
            ) 
            ->add('etatAbonnement')
            ->add('pack')       
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
