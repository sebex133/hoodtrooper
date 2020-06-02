<?php

namespace App\Entity;

use App\Repository\HoodtrooperPlaceCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HoodtrooperPlaceCommentRepository::class)
 */
class HoodtrooperPlaceComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=HoodtrooperUser::class, inversedBy="hoodtrooperUserPlaceComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment_author;

    /**
     * @ORM\ManyToOne(targetEntity=HoodtrooperPlace::class, inversedBy="hoodtrooperPlaceComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $comment_related_place;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment_text;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentAuthor(): ?HoodtrooperUser
    {
        return $this->comment_author;
    }

    public function setCommentAuthor(?HoodtrooperUser $comment_author): self
    {
        $this->comment_author = $comment_author;

        return $this;
    }

    public function getCommentRelatedPlace(): ?HoodtrooperPlace
    {
        return $this->comment_related_place;
    }

    public function setCommentRelatedPlace(?HoodtrooperPlace $comment_related_place): self
    {
        $this->comment_related_place = $comment_related_place;

        return $this;
    }

    public function getCommentText(): ?string
    {
        return $this->comment_text;
    }

    public function setCommentText(string $comment_text): self
    {
        $this->comment_text = $comment_text;

        return $this;
    }
}
