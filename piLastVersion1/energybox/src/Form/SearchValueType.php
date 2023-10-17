<?php

namespace App\Form;

use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'attr' => ['placeholder' => 'Search'],
                'label' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Search',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
