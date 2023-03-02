<?php

namespace App\Entity;

use App\Repository\RaspPicRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: RaspPicRepository::class)]
class RaspPic
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: 'uuid', unique: true)]
    private ?Uuid $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $picName;

    #[Vich\UploadableField(mapping: 'raspPic', fileNameProperty: 'picName')]
    #[Assert\File(maxSize: '8192k', mimeTypes: ["image/jpeg", "image/png"])]
    private $picFile;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'raspPics')]
    private ?RaspProject $raspProject = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getPicName(): ?string
    {
        return $this->picName;
    }

    public function setPicName(?string $picName): static
    {
        $this->picName = $picName;

        return $this;
    }

    /**
     * Get the value of imageFile
     * @return  mixed
     */
    public function getPicFile()
    {
        return $this->picFile;
    }

    /**
     * Set the value of imageFile
     * @param mixed $picFile
     * @return  self
     */
    public function setPicFile($picFile)
    {
        $this->picFile = $picFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($picFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTimeImmutable();
        }

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

    public function getRaspProject(): ?RaspProject
    {
        return $this->raspProject;
    }

    public function setRaspProject(?RaspProject $raspProject): self
    {
        $this->raspProject = $raspProject;

        return $this;
    }
}
