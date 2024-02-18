<?php

namespace App\Entity;

use App\Repository\InvitacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvitacionRepository::class)]
class Invitacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: GrupoAmigos::class, inversedBy: 'invitacions')]
    private Collection $grupoAmigos;

    #[ORM\ManyToOne(inversedBy: 'invitacionesRecibidas')]
    private ?User $usuarioInvitado = null;

    #[ORM\ManyToOne(inversedBy: 'invitacions')]
    private ?User $usuarioCreador = null;

    #[ORM\Column]
    private ?bool $aceptada = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaCreacion = null;

    public function __construct()
    {
        $this->grupoAmigos = new ArrayCollection();
        $this->fechaCreacion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, GrupoAmigos>
     */
    public function getGrupoAmigos(): Collection
    {
        return $this->grupoAmigos;
    }

    public function addGrupoAmigo(GrupoAmigos $grupoAmigo): static
    {
        if (!$this->grupoAmigos->contains($grupoAmigo)) {
            $this->grupoAmigos->add($grupoAmigo);
        }

        return $this;
    }

    public function removeGrupoAmigo(GrupoAmigos $grupoAmigo): static
    {
        $this->grupoAmigos->removeElement($grupoAmigo);

        return $this;
    }

    public function getUsuarioInvitado(): ?User
    {
        return $this->usuarioInvitado;
    }

    public function setUsuarioInvitado(?User $usuarioInvitado): static
    {
        $this->usuarioInvitado = $usuarioInvitado;

        return $this;
    }

    public function getUsuarioCreador(): ?User
    {
        return $this->usuarioCreador;
    }

    public function setUsuarioCreador(?User $usuarioCreador): static
    {
        $this->usuarioCreador = $usuarioCreador;

        return $this;
    }

    public function isAceptada(): ?bool
    {
        return $this->aceptada;
    }

    public function setAceptada(bool $aceptada): static
    {
        $this->aceptada = $aceptada;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): static
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }
}
