<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Copy;
use App\Repository\BookRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CopyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reference', TextType::class, [
                'label' => 'Référence',
            ])
            // ->add('book')
            ->add('book', EntityType::class, [
                'class' => Book::class,
            'query_builder' => function (BookRepository $r) {
                // Pour avoir les livres triés par ordre Alphabétique
                return $r->createQueryBuilder('i')
                    ->orderBy('i.title', 'ASC');
            },
            ])
            ->add('numberOfCopies', IntegerType::class, [
                'label' => 'Nombre d\'exemplaire',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Copy::class,
        ]);
    }
}
