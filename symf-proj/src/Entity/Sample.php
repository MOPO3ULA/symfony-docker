<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SampleRepository")
 */
class Sample
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="Genre")
     */
    private $genre;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $userLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bpm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $keyCreatedWith;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $createdWith;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $size;

    /**
     * @return mixed
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     * @return Sample
     */
    public function setSize($size): self
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     * @return Sample
     */
    public function setLength($length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getId(): ?int
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

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getUserLink(): ?string
    {
        return $this->userLink;
    }

    public function setUserLink(string $userLink): self
    {
        $this->userLink = $userLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBpm(): ?string
    {
        return $this->bpm;
    }

    public function setBpm(string $bpm): self
    {
        $this->bpm = $bpm;

        return $this;
    }

    public function getKeyCreatedWith(): ?string
    {
        return $this->keyCreatedWith;
    }

    public function setKeyCreatedWith(string $keyCreatedWith): self
    {
        $this->keyCreatedWith = $keyCreatedWith;

        return $this;
    }

    public function getCreatedWith(): ?string
    {
        return $this->createdWith;
    }

    public function setCreatedWith(string $createdWith): self
    {
        $this->createdWith = $createdWith;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }
}
