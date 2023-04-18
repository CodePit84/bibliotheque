<?php

namespace App\Form;

use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subscriptionNumber')
            // ->add('subscriptionDate')
            ->add('subscriptionDate', DateType::class, [
                'label' => 'Date d\'inscription',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('subscriptionAmount')
            // ->add('subscriptionStartDate')
            ->add('subscriptionStartDate', DateType::class, [
                'label' => 'Date de début d\'inscription',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            // ->add('subscriptionEndDate')
            // ->add('registeredUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
