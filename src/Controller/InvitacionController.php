<?php

namespace App\Controller;

use App\Entity\Invitacion;
use App\Form\InvitacionType;
use App\Repository\InvitacionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invitacion')]
class InvitacionController extends AbstractController
{
    #[Route('/', name: 'app_invitacion_index', methods: ['GET'])]
    public function index(InvitacionRepository $invitacionRepository): Response
    {
        return $this->render('invitacion/index.html.twig', [
            'invitacions' => $invitacionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invitacion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invitacion = new Invitacion();
        $form = $this->createForm(InvitacionType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invitacion);
            $entityManager->flush();

            return $this->redirectToRoute('app_invitacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invitacion/new.html.twig', [
            'invitacion' => $invitacion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invitacion_show', methods: ['GET'])]
    public function show(Invitacion $invitacion): Response
    {
        return $this->render('invitacion/show.html.twig', [
            'invitacion' => $invitacion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invitacion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invitacion $invitacion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvitacionType::class, $invitacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_invitacion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('invitacion/edit.html.twig', [
            'invitacion' => $invitacion,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_invitacion_delete', methods: ['POST'])]
    public function delete(Request $request, Invitacion $invitacion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitacion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invitacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invitacion_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/aceptar_invitacion/{id}', name: 'aceptar_invitacion', methods: ['POST'])]
    public function aceptarInvitacion( $id,Request $request, Invitacion $invitacion, EntityManagerInterface $entityManager): Response
    {
        // Lógica para aceptar o rechazar la invitación
        // ...
        $invitacion=$entityManager->getRepository(Invitacion::class)->find($id);
        $invitacion->setAceptada(true);
        $entityManager->persist($invitacion);
        $entityManager->flush();

        return $this->redirectToRoute('app_main'); // Puedes redirigir a donde sea necesario
    }
    
}
