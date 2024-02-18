<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Emparejamiento::class, mappedBy: 'usuarioRegala')]
    private Collection $participaciones;

    #[ORM\OneToMany(targetEntity: Emparejamiento::class, mappedBy: 'usuarioRecibe')]
    private Collection $regalos;



   

    public function __construct()
    {
        $this->participaciones = new ArrayCollection();
        $this->regalos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Emparejamiento>
     */
    public function getParticipaciones(): Collection
    {
        return $this->participaciones;
    }

    public function addParticipacione(Emparejamiento $participacione): static
    {
        if (!$this->participaciones->contains($participacione)) {
            $this->participaciones->add($participacione);
            $participacione->setUsuarioRegala($this);
        }

        return $this;
    }

    public function removeParticipacione(Emparejamiento $participacione): static
    {
        if ($this->participaciones->removeElement($participacione)) {
            // set the owning side to null (unless already changed)
            if ($participacione->getUsuarioRegala() === $this) {
                $participacione->setUsuarioRegala(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emparejamiento>
     */
    public function getRegalos(): Collection
    {
        return $this->regalos;
    }

    public function addRegalo(Emparejamiento $regalo): static
    {
        if (!$this->regalos->contains($regalo)) {
            $this->regalos->add($regalo);
            $regalo->setUsuarioRecibe($this);
        }

        return $this;
    }

    public function removeRegalo(Emparejamiento $regalo): static
    {
        if ($this->regalos->removeElement($regalo)) {
            // set the owning side to null (unless already changed)
            if ($regalo->getUsuarioRecibe() === $this) {
                $regalo->setUsuarioRecibe(null);
            }
        }

        return $this;
    }





}
