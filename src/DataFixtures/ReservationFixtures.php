<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReservationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();

        // ...
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            RideFixtures::class,
        ];
    }
}