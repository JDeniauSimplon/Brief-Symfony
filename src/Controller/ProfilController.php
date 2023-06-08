<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ProfileFormType;
use App\Entity\Car;
use App\Form\CarFormType;
use App\Form\RideFormType;
use App\Entity\Ride;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        $user = $this->getUser();
        $cars = $user->getCars();

        return $this->render('profil/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/profil/rides', name: 'app_profil_rides')]
    public function createRide(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $ride = new Ride();

        // Set the driver (current user) as the driver of the ride
        $user = $security->getUser();
        $ride->setDriver($user);

        $form = $this->createForm(RideFormType::class, $ride);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ride);
            $entityManager->flush();

            // Ajoutez un message flash ou toute autre logique de notification pour informer l'utilisateur de l'opération réussie

            return $this->redirectToRoute('app_profil_rides');
        }

        return $this->render('profil/rides/create.html.twig', [
            'rideForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/car', name: 'app_profil_car', methods: ["GET", "POST"])]
    public function manageCar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        
        // Retrieve the current logged in user
        $user = $this->getUser();
        
        // Set the owner of the car to the current user
        $car->setOwner($user);
    
        $cars = $this->getUser()->getCars();
    
        $form = $this->createForm(CarFormType::class, $car);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if a car with the same unique identifier already exists
            $existingCar = $entityManager->getRepository(Car::class)->findOneBy(['id' => $car->getId()]);
    
            if ($existingCar) {
                // Update the existing car with the submitted data
                $existingCar->setBrand($car->getBrand());
                $existingCar->setModel($car->getModel());
                $existingCar->setSeats($car->getSeats());
                // ... set other car properties
                
                $entityManager->flush();
            } else {
                // The car is new, persist it in the database
                $entityManager->persist($car);
                $entityManager->flush();
            }
    
            // Add a flash message or something else to notify the user about the successful operation
            return $this->redirectToRoute('app_profil_car');
        }
    
        return $this->render('profil/informations/car.html.twig', [
            'carForm' => $form->createView(),
            'cars' => $cars,
        ]);
    }

    #[Route('/profil/car/edit/{id}', name: "app_profil_edit_car", methods: ["GET", "POST"])]
    public function editCar(Car $car, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarFormType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profil_car');
        }

        $user = $this->getUser();
        $cars = $user->getCars();

        return $this->render('profil/informations/car.html.twig', [
            'carForm' => $form->createView(),
            'cars' => $cars,
        ]);
    }

    #[Route("/profil/car/delete/{id}", name: "app_profil_delete_car", methods: ["GET"])]
    public function deleteCar(Car $car, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($car);
        $entityManager->flush();

        return $this->redirectToRoute('app_profil_car');
    }

    #[Route('/profil/informations', name: 'app_profil_informations')]
    public function editInformations(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the plaintext password from the form
            $plainPassword = $form->get('plainPassword')->getData();
            if (!empty($plainPassword)) {
                $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            }
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/informations/informations.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView()
        ]);
    }
}