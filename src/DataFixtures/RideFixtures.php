<?php

namespace App\DataFixtures;

use App\Entity\Ride;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RideFixtures extends AbstractFixture implements DependentFixtureInterface 
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i ++) {
            // Générer un nombre aléatoire de ride pour chaque utilisateur
            $numRides = rand(0, 1); // Générer 0 ou 1 ride
    
            for ($j = 0; $j < $numRides; $j++) {
                $ride = new Ride();
                $ride->setDeparture($this->faker->city);
                $ride->setDestination($this->faker->city);
                $ride->setSeats($this->faker->numberBetween($min = 2, $max = 7));
                $ride->setPrice($this->faker->numberBetween($min = 1, $max = 20));
                $ride->setDate($this->faker->dateTimeThisYear());
                $ride->setCreated($this->faker->dateTimeThisYear());
    
                // Récupérer une référence d'utilisateur comme propriétaire
                $userReference = $this->getReference('user_' . $i);
                $ride->setDriver($userReference);
    
                $manager->persist($ride);
            }
        }
    
        $manager->flush();
}

    public function getDependencies()
{
    return [
        UserFixtures::class,
    ];
}
}