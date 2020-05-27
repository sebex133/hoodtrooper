<?php

namespace App\Entity;

use App\Repository\HoodtrooperPlaceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HoodtrooperPlaceRepository::class)
 */
class HoodtrooperPlace
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
     * @ORM\Column(type="string", length=255)
     */
    private $coordinates_latlng;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $description;

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

    public function getCoordinatesLatlng(): ?string
    {
        return $this->coordinates_latlng;
    }

    public function setCoordinatesLatlng(string $coordinates_latlng): self
    {
        $this->coordinates_latlng = $coordinates_latlng;

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
}
