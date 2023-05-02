<?php

namespace App\DataFixtures;

use Faker;
use DateInterval;
use App\Entity\Book;
use App\Entity\Copy;
use App\Entity\Author;
use App\Entity\Borrow;
use App\Entity\Gender;
use App\Entity\Country;
use App\Entity\RegisteredUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // Les Pays
        $countries= [];

        for ($i=1; $i <=100 ; $i++) { 
            $country = new Country();
            $country->setName($faker->country());
            
            $countries[] = $country;

            $manager->persist($country);
        }

        // Les Genres
        $genders = [];

        $gender1 = new Gender();
        $gender1->setName('Autobiographie');
        $genders[] = $gender1 ;
        $manager->persist($gender1);

        $gender2 = new Gender();
        $gender2->setName('Biographie');
        $genders[] = $gender2 ;
        $manager->persist($gender2);

        $gender3 = new Gender();
        $gender3->setName('Conte');
        $genders[] = $gender3 ;
        $manager->persist($gender3);

        $gender4 = new Gender();
        $gender4->setName('Epistolaire');
        $genders[] = $gender4 ;
        $manager->persist($gender4);

        $gender5 = new Gender();
        $gender5->setName('Essai');
        $genders[] = $gender5 ;
        $manager->persist($gender5);

        $gender6 = new Gender();
        $gender6->setName('Fable');
        $genders[] = $gender6 ;
        $manager->persist($gender6);

        $gender7 = new Gender();
        $gender7->setName('Nouvelle');
        $genders[] = $gender7 ;
        $manager->persist($gender7);

        $gender8 = new Gender();
        $gender8->setName('Pamphlet');
        $genders[] = $gender8 ;
        $manager->persist($gender8);

        $gender9 = new Gender();
        $gender9->setName('Roman');
        $genders[] = $gender9 ;
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
        $books= [];

        for ($i=1; $i <=150 ; $i++) { 
            $book = new Book();
            // $book->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true));
            $book->setTitle($faker->words(4, true));
            $book->setReleaseDate($faker->dateTime($max = 'now', $timezone = null));
            $book->setSummary($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            $book->setGender($genders[mt_rand(0, 8)]);
            $book->addAuthor($authors[mt_rand(0, 24)]);

            $books[] = $book;

            $manager->persist($book);
        }

        // Les Abonnés
        $registeredUsers = [];

        for ($i=1; $i <=50 ; $i++) { 
            $registeredUser = new RegisteredUser();
            // $book->setTitle($faker->sentence($nbWords = 4, $variableNbWords = true));
            $registeredUser->setLastName($faker->lastName());
            $registeredUser->setFirstName($faker->firstName());
            $registeredUser->setAddress($faker->streetAddress());
            $registeredUser->setZipcode(str_replace(' ', '', $faker->postcode));
            $registeredUser->setCity($faker->city());
            $registeredUser->setPhone(0 . mt_rand(111111111, 999999999));
            $registeredUser->setEmail($faker->email());
            $registeredUser->setAmount('10');
            $registeredUser->setSubscriptionStartDate($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null));

                $subscriptionStartDate = $registeredUser->getSubscriptionStartDate();
                $subscriptionEndDate = clone $subscriptionStartDate;
                $subscriptionEndDate = $subscriptionEndDate->add(new DateInterval('P1Y'));

                $registeredUser->setSubscriptionEndDate($subscriptionEndDate);

            $registeredUsers[] = $registeredUser;

            $manager->persist($registeredUser);
        }

        // Les Exemplaires
        $copies = [];

        for ($i=1; $i <=100 ; $i++) { 
            $copy = new Copy();
            $copy->setReference($faker->bothify('??###???'));
            $copy->setBook($books[mt_rand(0, 149)]);
            $copy->setNumberOfCopies(mt_rand(0, 5));

            $copies[] = $copy;

            $manager->persist($copy);
        }

        // Les Emprunts

        for ($i=1; $i <=100 ; $i++) { 
            $borrow = new Borrow();
            $borrow->setBorrowingPeriod(30);
            $borrow->setRegisteredUser($registeredUsers[mt_rand(0, 49)]);
            $borrow->setCopy($copies[mt_rand(0, 99)]);
            $borrow->setBorrowingDate($faker->dateTimeThisYear($max = 'now', $timezone = null));
            $borrow->setBorrowingEndDate($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null));
            $borrow->setReturned(mt_rand(0, 1));

            $manager->persist($borrow);
        }



        // Ajout d'un Pays d'origine à Author
            // for ($k=0; $k < count($author); $k++) {
            // for ($k=0; $k < 1; $k++) {    
            //     $author->setNativeCountry($countries[mt_rand(0, 24)]);
            // }

        $manager->flush();
    }
}