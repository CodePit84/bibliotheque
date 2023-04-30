<?php

namespace App\Form;

use App\Entity\Borrow;
use App\Entity\RegisteredUser;
use Symfony\Component\Form\AbstractType;
use App\Repository\RegisteredUserRepository;
use DateTime;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Date;

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
