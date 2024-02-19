<?php

namespace App\Controller;

use App\Entity\Emparejamiento;
use App\Repository\InvitacionRepository;
use App\Repository\NotificacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager, InvitacionRepository $invitacionRepository, NotificacionRepository $notificacionRepository): Response
    {
        $user= $this->getUser();
        if($user == null){
            return $this->redirectToRoute('app_login');
        }
        $invitaciones = $invitacionRepository->findBy(['usuarioInvitado' => $this->getUser(), 'aceptada' => false]);
        // dd($invitaciones);
        $emparejamientos= $entityManager->getRepository(Emparejamiento::class)->findBy(['usuarioRegala' => $user]);
        $notificaciones = $notificacionRepository->findBy(['usuarioDestino' => $user]);
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'user' => $user,
            'emparejamientos' => $emparejamientos,
            'invitaciones' => $invitaciones,
            'notificaciones' => $notificaciones
        ]);
    }
}
