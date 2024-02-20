<?php

namespace App\Entity;

use App\Repository\EmparejamientoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmparejamientoRepository::class)]
class Emparejamiento
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participaciones', cascade: ['persist'])]
    private ?User $usuarioRegala = null;

    #[ORM\ManyToOne(inversedBy: 'regalos', cascade: ['persist'])]
    private ?User $usuarioRecibe = null;

    #[ORM\ManyToOne(inversedBy: 'emparejamientos', cascade: ['persist'])]
    private ?Sorteo $sorteo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuarioRegala(): ?User
    {
        return $this->usuarioRegala;
    }

    public function setUsuarioRegala(?User $usuarioRegala): static
    {
        $this->usuarioRegala = $usuarioRegala;

        return $this;
    }

    public function getUsuarioRecibe(): ?User
    {
        return $this->usuarioRecibe;
    }

    public function setUsuarioRecibe(?User $usuarioRecibe): static
    {
        $this->usuarioRecibe = $usuarioRecibe;

        return $this;
    }

    public function getSorteo(): ?Sorteo
    {
        return $this->sorteo;
    }

    public function setSorteo(?Sorteo $sorteo): static
    {
        $this->sorteo = $sorteo;

        return $this;
    }
}
