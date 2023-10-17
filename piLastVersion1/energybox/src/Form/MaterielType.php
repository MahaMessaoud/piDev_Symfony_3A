<?php

namespace App\Form; 

use App\Entity\Materiel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMateriel',TextType::class,array('label'=>"Nom du Materiel",'attr'=>array('placeholder'=>'Taper Nom du Materiel','class'=>'form-control')))
            ->add('referenceMateriel',TextType::class,array('label'=>"Reference du Materiel",'attr'=>array('placeholder'=>'Taper Description du Materiel','class'=>'form-control')))
            //->add('dateMaintenanceMateriel',DateTimeType::class,array('attr'=>array('class' => 'form-control')))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}
