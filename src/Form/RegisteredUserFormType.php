<?php

namespace App\Form;

use App\Entity\RegisteredUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegisteredUserFormType extends AbstractType
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
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('zipcode', TextType::class, [
                'label' => 'Code Postal',
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
            ])
            ->add('number')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisteredUser::class,
        ]);
    }
}
