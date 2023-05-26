<?php

namespace App\DataFixtures;

use App\Entity\Rule;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RuleFixtures extends AbstractFixture implements DependentFixtureInterface 
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 30; $i ++) {
            $rule = new Rule();
            $rule->setName($this->faker->word);
            $rule->setDescription($this->faker->word);
            
            // Récupérer une référence d'utilisateur comme propriétaire
            $userReference = $this->getReference('user_' . $i);
            $rule->setAuthor($userReference);

            $manager->persist($rule);
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