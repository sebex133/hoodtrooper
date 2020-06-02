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
    private $coordinate_lat;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coordinate_lng;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $recommendation_level;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $place_image_filename;

    /**
     * @ORM\Column(type="date")
     */
    private $discover_date;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $private_area_place;

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

    public function getCoordinateLat(): ?string
    {
        return $this->coordinate_lat;
    }

    public function setCoordinateLat(string $coordinate_lat): self
    {
        $this->coordinate_lat = $coordinate_lat;

        return $this;
    }

    public function getCoordinateLng(): ?string
    {
        return $this->coordinate_lng;
    }

    public function setCoordinateLng(string $coordinate_lng): self
    {
        $this->coordinate_lng = $coordinate_lng;

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

    public function getRecommendationLevel(): ?string
    {
        return $this->recommendation_level;
    }

    public function setRecommendationLevel(?string $recommendation_level): self
    {
        $this->recommendation_level = $recommendation_level;

        return $this;
    }

    public function getPlaceImageFilename(): ?string
    {
        return $this->place_image_filename;
    }

    public function setPlaceImageFilename(?string $placeImageFilename): self
    {
        $this->place_image_filename = $placeImageFilename;

        return $this;
    }

    public function getDiscoverDate(): ?\DateTimeInterface
    {
        return $this->discover_date;
    }

    public function setDiscoverDate(\DateTimeInterface $discover_date): self
    {
        $this->discover_date = $discover_date;

        return $this;
    }

    public function getPrivateAreaPlace(): ?bool
    {
        return $this->private_area_place;
    }

    public function setPrivateAreaPlace(?bool $private_area_place): self
    {
        $this->private_area_place = $private_area_place;

        return $this;
    }
}
