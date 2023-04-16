<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Country;
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

        // Les Auteurs
        for ($i=1; $i <=25 ; $i++) { 
            $author = new Author();
            $author->setlastName($faker->lastName());
            $author->setfirstName($faker->firstName());
            // $author->setDateOfBirth($faker->date($format = 'Y-m-d', $max = 'now'));
            $author->setDateOfBirth($faker->dateTime($max = 'now', $timezone = null));
            $author->setBiography($faker->text($maxNbChars = 500));
            $author->setNativeCountry($countries[mt_rand(0, 24)]);

            $manager->persist($author);
        }

        // Ajout d'un Pays d'origine à Author
            // for ($k=0; $k < count($author); $k++) {
            // for ($k=0; $k < 1; $k++) {    
            //     $author->setNativeCountry($countries[mt_rand(0, 24)]);
            // }

        $manager->flush();
    }
}
