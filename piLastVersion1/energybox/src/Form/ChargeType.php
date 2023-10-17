<?php

namespace App\Form;

use App\Entity\Charge;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChargeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantiteCharge',NumberType::class,array('label'=>"Quantite du charge",'attr'=>array('placeholder'=>'Taper Quantite du Charge','class'=>'form-control')))
            ->add('dateArrivageCharge',DateTimeType::class,array('attr'=>array('class' => 'form-control')))
        ->add('materiel',null,array('label'=>"Materiel",'attr'=>array('class'=>'form-control')))
        ->add('fournisseur',null,array('label'=>"Fournisseur",'attr'=>array('class'=>'form-control')))
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Charge::class,
        ]);
    }
}
