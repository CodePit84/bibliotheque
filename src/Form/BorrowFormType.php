<?php

namespace App\Form;

use App\Entity\Borrow;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BorrowFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('borrowingPeriod')
            ->add('borrowingDate')
            // ->add('borrowingDate', DateType::class, [
            //     'label' => 'Date de l\'emprunt',
            //     'widget' => 'single_text',
            //     'format' => 'yyyy-MM-dd',
            // ])
            ->add('registeredUser')
            ->add('copy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
