<?php

namespace App\Form;

use DateTime;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BorrowFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $todaysDate = new Date() ;
        
        $builder
            ->add('borrowingPeriod', IntegerType::class, [
                'attr' => [
                    'value' => 30,
                    'disabled' => "",
                ],
            ])
            ->add('borrowingDate', DateType::class, [
                'label' => 'Date de naisance',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            // ->add('registeredUser')
            ->add('registeredUser', EntityType::class, [
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
                    // ->setParameter('id', $this->token->getToken()->getUser());
                    // ->setParameter('user_id', $this->token->getToken()->getUser()->getId());
            },
            ])
            // ->add('copy')
            ->add('copy', EntityType::class, [
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
