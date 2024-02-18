<?php

namespace App\Controller;

use App\Entity\Emparejamiento;
use App\Repository\InvitacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager, InvitacionRepository $invitacionRepository): Response
    {
        $user= $this->getUser();
        $invitaciones = $invitacionRepository->findBy(['usuarioInvitado' => $this->getUser(), 'aceptada' => false]);
        // dd($invitaciones);
        $emparejamientos= $entityManager->getRepository(Emparejamiento::class)->findBy(['usuarioRegala' => $user]);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'user' => $user,
            'emparejamientos' => $emparejamientos,
            'invitaciones' => $invitaciones
        ]);
    }
}
