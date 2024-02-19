<?php

namespace App\Controller;

use App\Entity\GrupoAmigos;
use App\Entity\Invitacion;
use App\Entity\Notificacion;
use App\Entity\User;
use App\Form\GrupoAmigosType;
use App\Repository\GrupoAmigosRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/grupo/amigos')]
class GrupoAmigosController extends AbstractController
{
    #[Route('/', name: 'app_grupo_amigos_index', methods: ['GET'])]
    public function index(GrupoAmigosRepository $grupoAmigosRepository): Response
    {
        return $this->render('grupo_amigos/index.html.twig', [
            'grupo_amigos' => $grupoAmigosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_grupo_amigos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $user = $this->getUser();
        $grupoAmigo = new GrupoAmigos();
        $form = $this->createForm(GrupoAmigosType::class, $grupoAmigo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Guardar el grupo primero
            $entityManager->persist($grupoAmigo);
            $entityManager->flush();

            // Crear invitaciones y enviar notificaciones
            foreach ($grupoAmigo->getUsuarios() as $usuarioInvitado) {
                $invitacion = new Invitacion();
                $invitacion
                    ->addGrupoAmigo($grupoAmigo)
                    ->setUsuarioCreador($user)
                    ->setUsuarioInvitado($usuarioInvitado)
                    ->setAceptada(false);

                $notificacion = new Notificacion();
                $notificacion
                    ->setUsuarioDestino($usuarioInvitado)
                    ->setMensaje("Has recibido una invitación para unirte al grupo de amigos '{$grupoAmigo->getNombre()}'")
                    ->setLeida(false);

                // Guardar invitación y notificación
                $entityManager->persist($invitacion);
                $entityManager->persist($notificacion);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_grupo_amigos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('grupo_amigos/new.html.twig', [
            'grupo_amigo' => $grupoAmigo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_amigos_show', methods: ['GET'])]
    public function show(GrupoAmigos $grupoAmigo): Response
    {
        return $this->render('grupo_amigos/show.html.twig', [
            'grupo_amigo' => $grupoAmigo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grupo_amigos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GrupoAmigos $grupoAmigo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GrupoAmigosType::class, $grupoAmigo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_grupo_amigos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('grupo_amigos/edit.html.twig', [
            'grupo_amigo' => $grupoAmigo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grupo_amigos_delete', methods: ['POST'])]
    public function delete(Request $request, GrupoAmigos $grupoAmigo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $grupoAmigo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($grupoAmigo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_grupo_amigos_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/mostrar_buscador_usuarios', name: 'mostrar_buscador_usuarios', methods: ['GET'])]
    public function mostrarBuscadorUsuarios(Request $request, GrupoAmigos $grupoAmigo, UserRepository $userRepository): Response
    {
        $username = $request->request->get('username');
    
        // Obtener usuarios que coinciden con el nombre y no están en el grupo
        $usuariosEncontrados = $userRepository->buscarUsuariosPorNombreYGrupo($username, $grupoAmigo);

        return $this->render('grupo_amigos/mostrar_buscador_usuarios.html.twig', [
            'grupo_amigo' => $grupoAmigo,
            'usuariosEncontrados' => $usuariosEncontrados,
        ]);
    }

    #[Route('/{id}/buscar_usuarios', name: 'buscar_usuarios', methods: ['POST'])]
    public function buscarUsuarios(Request $request, GrupoAmigos $grupoAmigo, UserRepository $userRepository): Response
    {
        $username = $request->request->get('username');
        $usuariosEncontrados = $userRepository->buscarUsuariosPorNombreExcluyendoGrupo($username, $grupoAmigo);

        return $this->render('grupo_amigos/mostrar_buscador_usuarios.html.twig', [
            'grupo_amigo' => $grupoAmigo,
            'usuariosEncontrados' => $usuariosEncontrados,
        ]);
    }

    #[Route('/{idGrupo}/agregar_usuario/{idUsuario}', name: 'agregar_usuario_a_grupo', methods: ['GET'])]
    public function agregarUsuarioAGrupo(int $idGrupo, int $idUsuario, EntityManagerInterface $entityManager): Response
    {
        // Obtener las instancias de GrupoAmigos y User a partir de los IDs
        $grupoAmigo = $entityManager->getRepository(GrupoAmigos::class)->find($idGrupo);
        $usuario = $entityManager->getRepository(User::class)->find($idUsuario);
    
        // Verificar si las instancias son válidas
        if (!$grupoAmigo || !$usuario) {
            // Manejar el caso en el que no se encuentre el grupo o el usuario
            // Puedes redirigir o lanzar una excepción según sea necesario
            return $this->redirectToRoute('app_grupo_amigos_index');
        }
    
        // Implementar la lógica para agregar el usuario al grupo
        $grupoAmigo->addUsuario($usuario);
        $invitacion = new Invitacion();
                $invitacion
                    ->addGrupoAmigo($grupoAmigo)
                    ->setUsuarioInvitado($usuario)
                    ->setUsuarioCreador($this->getUser())
                    ->setAceptada(false);

                $notificacion = new Notificacion();
                $notificacion
                    ->setUsuarioDestino($usuario)
                    ->setMensaje("Has recibido una invitación para unirte al grupo de amigos '{$grupoAmigo->getNombre()}'")
                    ->setLeida(false);
    
        // Persistir los cambios en la base de datos
        $entityManager->persist($invitacion);
        $entityManager->persist($notificacion);
        $entityManager->flush();
    
        // Redirigir de nuevo a la vista show del grupo
        return $this->redirectToRoute('app_grupo_amigos_show', ['id' => $grupoAmigo->getId()]);
    }
}
