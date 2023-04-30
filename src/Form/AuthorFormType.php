<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AuthorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('dateOfBirth', DateType::class, [
                'label' => 'Date de naisance',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                // 'format' => 'd/m/Y',
            ])
            // ->add('biography', TextType::class, [
            //     'label' => 'Biographie',
            // ])
            ->add('biography', TextareaType::class, [
                'label' => 'Biographie',
            ],
            )
            // ->add('nativeCountry')
            ->add('nativeCountry', EntityType::class, [
                'class' => Country::class,
            'query_builder' => function (CountryRepository $r) {
                // Pour avoir les pays triés par ordre Alphabétique
                return $r->createQueryBuilder('i')
                    ->orderBy('i.name', 'ASC');
            },
            ])
            // ->add('books')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Author::class,
        ]);
    }
}
