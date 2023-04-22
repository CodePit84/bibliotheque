<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
            ->add('author')
            // ->add('author', CollectionType::class, [
            //     'entry_type' => AuthorType::class,
            //     'entry_options' => ['label' => false],
            //     'allow_add' => true,
            // ])
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
            ->add('summary', textType::class, [
                'label' => 'Résumé',
            ],
            )
            // ->add('type', textType::class, [
            //     'label' => 'Type (Classification décimale de Dewey)',
            //     'attr' => array(
            //         'placeholder' => 'Exemple : RP843DAN'
            //     )
            // ],
            // )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
