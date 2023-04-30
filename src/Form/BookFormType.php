<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Gender;
use App\Repository\AuthorRepository;
use App\Repository\GenderRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '255',   
                ],
                'label' => 'Titre de l\'ouvrage',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2, 'max' => 180])
                    ]
            ],
            )
            // ->add('author')
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'multiple'=>'true',
            'query_builder' => function (AuthorRepository $r) {
                return $r->createQueryBuilder('i')
                    ->orderBy('i.lastName', 'ASC');
            },
            ])
            // ->add('releaseDate')
            ->add('releaseDate', DateType::class, [
                'label' => 'Date de sortie',
                'label_attr' => [
                    'class' => 'mt-4'  
                ],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                // 'format' => 'd/m/Y',
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé',
            ],
            )
            // ->add('gender')
            ->add('gender', EntityType::class, [
                'class' => Gender::class,
            'query_builder' => function (GenderRepository $rr) {
                return $rr->createQueryBuilder('g')
                    ->orderBy('g.name', 'ASC');
            },
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
