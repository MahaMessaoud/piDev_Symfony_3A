<?php

namespace App\Form;

use App\Entity\Fournisseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomFournisseur',TextType::class,array('label'=>"Nom du Fournisseur",'attr'=>array('placeholder'=>'Taper Nom du Fournisseur','class'=>'form-control')))
            ->add('contactFournisseur',NumberType::class,array('label'=>"Contact du Fournisseur",'attr'=>array('placeholder'=>'Taper Contact du Fournisseur','class'=>'form-control')))
            ->add('emailFournisseur',EmailType::class,array('label'=>"Email du Fournisseur",'attr'=>array('placeholder'=>'Taper Email du Fournisseur','class'=>'form-control')))
            ->add('adresseFournisseur',TextType::class,array('label'=>"Adresse du Fournisseur",'attr'=>array('placeholder'=>'Taper Description du Fournisseur','class'=>'form-control')))
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fournisseur::class,
        ]);
    }
}
