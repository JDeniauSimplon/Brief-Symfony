<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ride;

#[Route('/homepage')]

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $rideRepository = $entityManager->getRepository(Ride::class);
        $rides = $rideRepository->findAll();
        return $this->render('homepage/index.html.twig', [
            'controller_name' => "page d'accueil",
            'homeTitle' => "chamBlaCar",
            'rides' => $rides,
            
        ]);
    }
}
