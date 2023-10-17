<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class AbonnementExtraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAchat', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['min' => (new \DateTime())->format('Y-m-d')], // Ajoutez cette ligne
            ])
            ->add('pack')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}
