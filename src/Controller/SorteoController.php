<?php

namespace App\Controller;

use App\Entity\Emparejamiento;
use App\Entity\Sorteo;
use App\Entity\User;
use App\Form\SorteoType;
use App\Repository\EmparejamientoRepository;
use App\Repository\SorteoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/sorteo')]
class SorteoController extends AbstractController
{
     #[Route('/', name: 'app_sorteo_index', methods: ['GET'])]
    public function index(SorteoRepository $sorteoRepository): Response
    {
        return $this->render('sorteo/index.html.twig', [
            'sorteos' => $sorteoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sorteo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sorteo = new Sorteo();
        $form = $this->createForm(SorteoType::class, $sorteo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sorteo->setStatus('no finalizado');
            $entityManager->persist($sorteo);
            $entityManager->flush();

            return $this->redirectToRoute('app_sorteo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorteo/new.html.twig', [
            'sorteo' => $sorteo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sorteo_show', methods: ['GET'])]
    public function show(Sorteo $sorteo): Response
    {
        return $this->render('sorteo/show.html.twig', [
            'sorteo' => $sorteo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sorteo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sorteo $sorteo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SorteoType::class, $sorteo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sorteo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sorteo/edit.html.twig', [
            'sorteo' => $sorteo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sorteo_delete', methods: ['POST'])]
    public function delete(Request $request, Sorteo $sorteo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sorteo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sorteo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sorteo_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/sorteo/{id}/realizar', name: 'sorteo_realizar')]
    public function realizar(int $id, SorteoRepository $sorteoRepository, EmparejamientoRepository $emparejamientoRepository, EntityManagerInterface $em, Request $request): Response
    {
        // Obtener usuarios participantes
        $sorteo = $sorteoRepository->find($id);
        $usuariosParticipantes = $em->getRepository(User::class)->findAll();

        // Asegurarse de que haya al menos dos usuarios para realizar el sorteo
        if (count($usuariosParticipantes) < 2) {
            $this->addFlash('error', 'Se necesitan al menos dos usuarios para realizar el sorteo.');
            return $this->redirectToRoute('app_sorteo_show', ['id' => $id]);
        }

        // Barajar aleatoriamente la lista de usuarios participantes
        shuffle($usuariosParticipantes);

        // Crear emparejamientos
        foreach ($usuariosParticipantes as $index => $usuario) {
            $destinatario = $usuariosParticipantes[($index + 1) % count($usuariosParticipantes)]; // Circular
            $emparejamiento = new Emparejamiento();
            $emparejamiento->setUsuarioRegala($usuario);
            $emparejamiento->setUsuarioRecibe($destinatario);
            $emparejamiento->setSorteo($sorteo);
            $em->persist($emparejamiento);
        }

        $sorteo->setStatus('finalizado');
        $em->flush();

        $this->addFlash('success', 'Sorteo realizado correctamente.');

        return $this->redirectToRoute('app_sorteo_show', ['id' => $id]);
    }

    #[Route('/{idSorteo}/buscador_usuarios_sorteo',name: 'buscador_usuarios_sorteo', methods: ['GET'])]public function buscarUsuarios($idSorteo, Request $request, UserRepository $userRepository,SorteoRepository $sorteoRepository): Response
    {
        // Cargar la entidad Sorteo usando el idSorteo
        $sorteo = $sorteoRepository->find($idSorteo);
    
        $username = $request->request->get('username');
        $usuariosEncontrados = $userRepository->findAll();
    
        return $this->render('sorteo/buscador_usuarios_sorteo.html.twig', [
            'sorteo' => $sorteo,
            'usuariosEncontrados' => $usuariosEncontrados,
        ]);
    }

    #[Route('/{idSorteo}/buscar_usuarios_sorteo', name: 'buscar_usuarios_sorteo', methods: ['POST'])]
    public function mostrarBuscadorUsuarios(Request $request, Sorteo $sorteo, UserRepository $userRepository): Response
    {
        $username = $request->request->get('username');
        $usuariosEncontrados = $userRepository->findAll();

        return $this->render('sorteo/buscador_usuarios_sorteo.html.twig', [
            'sorteo' => $sorteo,
            'usuariosEncontrados' => $usuariosEncontrados,
        ]);

    }

    #[Route('{idSorteo}/agregar_usuario_sorteo/{idUsuario}', name: 'agregar_usuario_sorteo', methods: ['GET'])]
    public function agregarUsuarioASorteo(int $idSorteo, int $idUsuario, EntityManagerInterface $entityManager): Response
    {
        // Obtener las instancias de Sorteo y User a partir de los IDs
        $sorteo = $entityManager->getRepository(Sorteo::class)->find($idSorteo);
        $usuario = $entityManager->getRepository(User::class)->find($idUsuario);

        // Agregar el usuario al sorteo
        $sorteo->addUsuario($usuario);

        $entityManager->persist($sorteo);
        $entityManager->flush();

        return $this->redirectToRoute('app_sorteo_show', ['id' => $idSorteo]);

    }
}