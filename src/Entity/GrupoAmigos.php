<?php

namespace App\Entity;

use App\Repository\GrupoAmigosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrupoAmigosRepository::class)]
class GrupoAmigos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'grupoAmigos')]
    private Collection $usuarios;


    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\OneToMany(targetEntity: Sorteo::class, mappedBy: 'grupoAmigos')]
    private Collection $sorteos;

    public function __construct()
    {
        $this->usuarios = new ArrayCollection();
        $this->sorteos = new ArrayCollection();
  
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsuarios(): Collection
    {
        return $this->usuarios;
    }

    public function addUsuario(User $usuario): static
    {
        if (!$this->usuarios->contains($usuario)) {
            $this->usuarios->add($usuario);
        }

        return $this;
    }

    public function removeUsuario(User $usuario): static
    {
        $this->usuarios->removeElement($usuario);

        return $this;
    }

   

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Sorteo>
     */
    public function getSorteos(): Collection
    {
        return $this->sorteos;
    }

    public function addSorteo(Sorteo $sorteo): static
    {
        if (!$this->sorteos->contains($sorteo)) {
            $this->sorteos->add($sorteo);
            $sorteo->setGrupoAmigos($this);
        }

        return $this;
    }

    public function removeSorteo(Sorteo $sorteo): static
    {
        if ($this->sorteos->removeElement($sorteo)) {
            // set the owning side to null (unless already changed)
            if ($sorteo->getGrupoAmigos() === $this) {
                $sorteo->setGrupoAmigos(null);
            }
        }

        return $this;
    }
}
