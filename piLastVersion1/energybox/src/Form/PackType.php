<?php

namespace App\Form;

use App\Entity\Pack;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             
            ->add('TypePack',TextType::class,array('label'=>"Type du pack:",'attr'=>array('placeholder'=>'Tapez  le type du pack','class'=>'form-control')))
           
            ->add('montantPack',TextType::class,array('label'=>"Montant du pack:",'attr'=>array('placeholder'=>'Tapez le montant du pack','class' => 'form-control')))
            ->add('DureePack',TextType::class,array('label'=>"Durée du pack:",'attr'=>array('placeholder'=>'Tapez  la durée du pack','class'=>'form-control')))
           
            ->add('disponibilitePack',TextType::class,array('label'=>"Disponibilité du pack:",'attr'=>array('placeholder'=>'Tapez la disponibilité du pack','class' => 'form-control')))
            ->add('descriptionPack',TextType::class,array('label'=>"Description du pack:",'attr'=>array('placeholder'=>'Tapez  la description du pack','class'=>'form-control')))
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pack::class,
        ]);
    }
}
