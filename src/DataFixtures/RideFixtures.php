<?php
    namespace App\DataFixtures;

    use App\Entity\Ride;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Faker\Factory;

class RideFixtures extends AbstractFixture implements DependentFixtureInterface {

    public function load (ObjectManager $manager):void {

        for ($i=0; $i < 30; $i++) { 
            $ride = new Ride();
            $ride->setDeparture($this->faker->word());
            $ride->setDestination($this->faker->word());
            $ride->setSeats($this->faker->numberBetween($min = 2, $max = 7));
            $ride->setPrice(round($this->faker->randomFloat(), 2));
            $ride->setDate($this->faker->dateTimeBetween('-1 year', 'now'));
            $ride->setCreated($this->faker->dateTimeBetween('-1 year', 'now'));

                // Récupérer une référence d'utilisateur comme propriétaire
                $userReference = $this->getReference('user_' . $i);
                $ride->setDriver($userReference);


                // Table intermediaire ride_rule
                $this->setReference('ride_' . $i, $ride);
                
            
            $manager->persist($ride);   
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}


?>