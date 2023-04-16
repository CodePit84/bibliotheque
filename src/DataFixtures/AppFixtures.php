<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i=1; $i <=25 ; $i++) { 
            $country = new Country();
            $country->setName($faker->country());
            
            $manager->persist($country);
        }

        $manager->flush();
    }
}
