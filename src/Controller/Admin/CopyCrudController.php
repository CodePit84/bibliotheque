<?php

namespace App\Controller\Admin;

use App\Entity\Copy;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CopyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Copy::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
