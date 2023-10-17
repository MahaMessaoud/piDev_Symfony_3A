<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           //->add('date')
            /*
            ->add('date', DateType::class, [
               'widget' => 'single_text',
               'html5' => false,
                'attr' => ['class' => 'datepicker'],
                'constraints' => [
                  new \Symfony\Component\Validator\Constraints\GreaterThanOrEqual(new \DateTime('today')),
                ],
            ])
            */
            ->add('date' , DateType::class, ['widget' => 'single_text','html5' => true,'attr' => ['min' => (new \DateTime())->format('Y-m-d')]
           // ->add('userid')
         //   ->add('idplat')
       ]) ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
