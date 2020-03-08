<?php

namespace App\Entity;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompetitionRepository")
 */
class Competition
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $winner;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\OneToOne(targetEntity="Sample")
     */
    private $sample;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $loopermanLink;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rating;

    /**
     * @return string
     */
    public function getLoopermanLink(): string
    {
        return $this->loopermanLink;
    }

    /**
     * @param mixed $loopermanLink
     * @return Competition
     */
    public function setLoopermanLink($loopermanLink): self
    {
        $this->loopermanLink = $loopermanLink;

        return $this;
    }

    /**
     * @return Sample
     */
    public function getSample(): Sample
    {
        return $this->sample;
    }

    /**
     * @param Sample $sample
     * @return Competition
     */
    public function setSample(Sample $sample): self
    {
        $this->sample = $sample;
        $this->setTitle($sample->getTitle());

        return $this;
    }

    public function __construct()
    {
        $this->startDate = new DateTime();

        $startDate = clone $this->startDate;
        $this->endDate = $startDate->add(new DateInterval('P2D'));
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

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
