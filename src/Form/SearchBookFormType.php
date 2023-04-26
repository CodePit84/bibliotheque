<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('words', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control mt-3',
                    'placeholder' => 'Entrez un ou des mots-clés'
                ]   
            ])
            ->add('Rechercher', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-3 mx-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
