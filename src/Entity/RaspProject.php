<?php

namespace App\Entity;

use App\Repository\RaspProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RaspProjectRepository::class)]
class RaspProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip_int = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ip_ext = null;

    #[ORM\ManyToOne(inversedBy: 'raspProjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'raspProject', targetEntity: RaspPic::class)]
    #[ORM\OrderBy(["createdAt" => "ASC"])]
    private Collection $raspPics;

    #[ORM\OneToOne(mappedBy: 'raspProject', cascade: ['persist', 'remove'])]
    private ?RaspAuthorization $raspAuthorization = null;

    #[ORM\Column(nullable: true)]
    private ?int $videoPort = null;

    #[ORM\Column(nullable: true)]
    private ?int $socketPort = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->raspPics = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getInternIP(): ?string
    {
        return $this->ip_int;
    }

    public function setInternIP(?string $ip_int): self
    {
        $this->ip_int = $ip_int;

        return $this;
    }

    public function getExternIP(): ?string
    {
        return $this->ip_ext;
    }

    public function setExternIP(?string $ip_ext): self
    {
        $this->ip_ext = $ip_ext;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, RaspPic>
     */
    public function getRaspPics(): Collection
    {
        return $this->raspPics;
    }

    public function addRaspPic(RaspPic $raspPic): self
    {
        if (!$this->raspPics->contains($raspPic)) {
            $this->raspPics->add($raspPic);
            $raspPic->setRaspProject($this);
        }

        return $this;
    }

    public function removeRaspPic(RaspPic $raspPic): self
    {
        if ($this->raspPics->removeElement($raspPic)) {
            // set the owning side to null (unless already changed)
            if ($raspPic->getRaspProject() === $this) {
                $raspPic->setRaspProject(null);
            }
        }

        return $this;
    }

    public function getRaspAuthorization(): ?RaspAuthorization
    {
        return $this->raspAuthorization;
    }

    public function setRaspAuthorization(?RaspAuthorization $raspAuthorization): self
    {
        // unset the owning side of the relation if necessary
        if ($raspAuthorization === null && $this->raspAuthorization !== null) {
            $this->raspAuthorization->setRaspProject(null);
        }

        // set the owning side of the relation if necessary
        if ($raspAuthorization !== null && $raspAuthorization->getRaspProject() !== $this) {
            $raspAuthorization->setRaspProject($this);
        }

        $this->raspAuthorization = $raspAuthorization;

        return $this;
    }

    public function getVideoPort(): ?int
    {
        return $this->videoPort;
    }

    public function setVideoPort(?int $videoPort): self
    {
        $this->videoPort = $videoPort;

        return $this;
    }

    public function getSocketPort(): ?int
    {
        return $this->socketPort;
    }

    public function setSocketPort(?int $socketPort): self
    {
        $this->socketPort = $socketPort;

        return $this;
    }
}
