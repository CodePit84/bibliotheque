<?php

namespace App\Controller\Admin;

use App\Entity\RegisteredUser;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RegisteredUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RegisteredUser::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('lastName'),
            TextField::new('firstName'),
            TextField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('subscriptionStartDate')
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
