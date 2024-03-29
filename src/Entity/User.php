<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: RaspProject::class, orphanRemoval: true)]
    private Collection $raspProjects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: RaspAuthorization::class)]
    private Collection $raspAuthorizations;

    #[ORM\ManyToMany(targetEntity: RaspProject::class, mappedBy: 'authorizedUsers')]
    private Collection $authorizedRasps;

    public function __construct()
    {
        $this->raspProjects = new ArrayCollection();
        $this->raspAuthorizations = new ArrayCollection();
        $this->authorizedRasps = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, RaspProject>
     */
    public function getRaspProjects(): Collection
    {
        return $this->raspProjects;
    }

    public function addRaspProject(RaspProject $raspProject): self
    {
        if (!$this->raspProjects->contains($raspProject)) {
            $this->raspProjects->add($raspProject);
            $raspProject->setAuthor($this);
        }

        return $this;
    }

    public function removeRaspProject(RaspProject $raspProject): self
    {
        if ($this->raspProjects->removeElement($raspProject)) {
            // set the owning side to null (unless already changed)
            if ($raspProject->getAuthor() === $this) {
                $raspProject->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RaspAuthorization>
     */
    public function getRaspAuthorizations(): Collection
    {
        return $this->raspAuthorizations;
    }

    public function addRaspAuthorization(RaspAuthorization $raspAuthorization): self
    {
        if (!$this->raspAuthorizations->contains($raspAuthorization)) {
            $this->raspAuthorizations->add($raspAuthorization);
            $raspAuthorization->setUser($this);
        }

        return $this;
    }

    public function removeRaspAuthorization(RaspAuthorization $raspAuthorization): self
    {
        if ($this->raspAuthorizations->removeElement($raspAuthorization)) {
            // set the owning side to null (unless already changed)
            if ($raspAuthorization->getUser() === $this) {
                $raspAuthorization->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RaspProject>
     */
    public function getAuthorizedRasps(): Collection
    {
        return $this->authorizedRasps;
    }

    public function addAuthorizedRasp(RaspProject $authorizedRasp): self
    {
        if (!$this->authorizedRasps->contains($authorizedRasp)) {
            $this->authorizedRasps->add($authorizedRasp);
            $authorizedRasp->addAuthorizedUser($this);
        }

        return $this;
    }

    public function removeAuthorizedRasp(RaspProject $authorizedRasp): self
    {
        if ($this->authorizedRasps->removeElement($authorizedRasp)) {
            $authorizedRasp->removeAuthorizedUser($this);
        }

        return $this;
    }
}
