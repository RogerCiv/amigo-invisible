<?php

namespace App\Controller;

use App\Entity\Emparejamiento;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user= $this->getUser();

        $emparejamientos= $entityManager->getRepository(Emparejamiento::class)->findBy(['usuarioRegala' => $user]);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'user' => $user,
            'emparejamientos' => $emparejamientos,
        ]);
    }
}
