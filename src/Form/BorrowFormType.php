<?php

namespace App\Form;

use App\Entity\Copy;
use App\Entity\Borrow;
use App\Entity\RegisteredUser;
use App\Repository\CopyRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\RegisteredUserRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class BorrowFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $todaysDate = new Date() ;
        
        $builder
            ->add('borrowingPeriod', IntegerType::class, [
                'label' => 'Durée d\'emprunt en jours',
                'attr' => [
                    'value' => 30,
                ],
            ])
            ->add('borrowingDate', DateType::class, [
                'label' => 'Date de l\'emprunt',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('registeredUser', EntityType::class, [
                'label' => 'Abonné (abonnement actif)',
                'attr' => [
                    'class' => 'select2 form-select'   
                ],
                'class' => RegisteredUser::class,
            'query_builder' => function (RegisteredUserRepository $r) {
                
                // Pour avoir uniquement les utilisateurs dont l'abonnement est valide et trié par ordre Alphabétique
                
                return $r->createQueryBuilder('i')
                    // ->where("i.subscriptionEndDate>='2024-04-04'");
                    ->where("i.subscriptionEndDate>=CURRENT_TIMESTAMP()")
                    ->orderBy('i.lastName', 'ASC');
            },
            ])
            ->add('copy', EntityType::class, [
                'label' => 'Réf exemplaire',
                'attr' => [
                    'class' => 'select2'   
                ],
                'class' => Copy::class,
            'query_builder' => function (CopyRepository $r) {
                return $r->createQueryBuilder('i')
                    ->orderBy('i.reference', 'ASC');
            },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Borrow::class,
        ]);
    }
}
