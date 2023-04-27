<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Country;
use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // Les Pays
        $countries= [];

        for ($i=1; $i <=25 ; $i++) { 
            $country = new Country();
            $country->setName($faker->country());
            
            $countries[] = $country;

            $manager->persist($country);
        }

        // Les Genres
        $gender1 = new Gender();
        $gender1->setName('Autobiographie');
        $manager->persist($gender1);

        $gender2 = new Gender();
        $gender2->setName('Biographie');
        $manager->persist($gender2);

        $gender3 = new Gender();
        $gender3->setName('Conte');
        $manager->persist($gender3);

        $gender4 = new Gender();
        $gender4->setName('Epistolaire');
        $manager->persist($gender4);

        $gender5 = new Gender();
        $gender5->setName('Essai');
        $manager->persist($gender5);

        $gender6 = new Gender();
        $gender6->setName('Fable');
        $manager->persist($gender6);

        $gender7 = new Gender();
        $gender7->setName('Nouvelle');
        $manager->persist($gender7);

        $gender8 = new Gender();
        $gender8->setName('Pamphlet');
        $manager->persist($gender8);

        $gender9 = new Gender();
        $gender9->setName('Roman');
        $manager->persist($gender9);
        

        // Les Auteurs
        $authors= [];

        for ($i=1; $i <=25 ; $i++) { 
            $author = new Author();
            $author->setlastName($faker->lastName());
            $author->setfirstName($faker->firstName());
            // $author->setDateOfBirth($faker->date($format = 'Y-m-d', $max = 'now'));
            $author->setDateOfBirth($faker->dateTime($max = 'now', $timezone = null));
            $author->setBiography($faker->text($maxNbChars = 500));
            $author->setNativeCountry($countries[mt_rand(0, 24)]);

            $authors[] = $author;

            $manager->persist($author);
        }

        // Les Livres

        for ($i=1; $i <=25 ; $i++) { 
            $book = new Book();
            // $book->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true));
            $book->setTitle($faker->words(4, true));
            $book->setType('Type');
            $book->addAuthor($authors[mt_rand(0, 24)]);

            $manager->persist($book);
        }


        // Ajout d'un Pays d'origine à Author
            // for ($k=0; $k < count($author); $k++) {
            // for ($k=0; $k < 1; $k++) {    
            //     $author->setNativeCountry($countries[mt_rand(0, 24)]);
            // }

        $manager->flush();
    }
}
