<?php

namespace App\Entity;

use App\Repository\HoodtrooperUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=HoodtrooperUserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @UniqueEntity(fields={"nickname"}, message="There is already an account with this nickname")
 */
class HoodtrooperUser implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $sex;

    /**
     * @ORM\Column(type="date")
     */
    private $birth_date;

    /**
     * @ORM\OneToMany(targetEntity=HoodtrooperPlace::class, mappedBy="author", orphanRemoval=true)
     */
    private $hoodtrooperUserAuthoredPlaces;

    /**
     * @ORM\OneToMany(targetEntity=HoodtrooperPlaceComment::class, mappedBy="comment_author", orphanRemoval=true)
     */
    private $hoodtrooperUserPlaceComments;

    public function __construct()
    {
        $this->hoodtrooperUserAuthoredPlaces = new ArrayCollection();
        $this->hoodtrooperUserPlaceComments = new ArrayCollection();
    }

    public function getId(): ?int
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
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    /**
     * @return Collection|HoodtrooperPlace[]
     */
    public function getHoodtrooperUserAuthoredPlaces(): Collection
    {
        return $this->hoodtrooperUserAuthoredPlaces;
    }

    public function addHoodtrooperUserAuthoredPlace(HoodtrooperPlace $hoodtrooperUserAuthoredPlace): self
    {
        if (!$this->hoodtrooperUserAuthoredPlaces->contains($hoodtrooperUserAuthoredPlace)) {
            $this->hoodtrooperUserAuthoredPlaces[] = $hoodtrooperUserAuthoredPlace;
            $hoodtrooperUserAuthoredPlace->setAuthor($this);
        }

        return $this;
    }

    public function removeHoodtrooperUserAuthoredPlace(HoodtrooperPlace $hoodtrooperUserAuthoredPlace): self
    {
        if ($this->hoodtrooperUserAuthoredPlaces->contains($hoodtrooperUserAuthoredPlace)) {
            $this->hoodtrooperUserAuthoredPlaces->removeElement($hoodtrooperUserAuthoredPlace);
            // set the owning side to null (unless already changed)
            if ($hoodtrooperUserAuthoredPlace->getAuthor() === $this) {
                $hoodtrooperUserAuthoredPlace->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|HoodtrooperPlaceComment[]
     */
    public function getHoodtrooperUserPlaceComments(): Collection
    {
        return $this->hoodtrooperUserPlaceComments;
    }

    public function addHoodtrooperUserPlaceComment(HoodtrooperPlaceComment $hoodtrooperUserPlaceComment): self
    {
        if (!$this->hoodtrooperUserPlaceComments->contains($hoodtrooperUserPlaceComment)) {
            $this->hoodtrooperUserPlaceComments[] = $hoodtrooperUserPlaceComment;
            $hoodtrooperUserPlaceComment->setCommentAuthor($this);
        }

        return $this;
    }

    public function removeHoodtrooperUserPlaceComment(HoodtrooperPlaceComment $hoodtrooperUserPlaceComment): self
    {
        if ($this->hoodtrooperUserPlaceComments->contains($hoodtrooperUserPlaceComment)) {
            $this->hoodtrooperUserPlaceComments->removeElement($hoodtrooperUserPlaceComment);
            // set the owning side to null (unless already changed)
            if ($hoodtrooperUserPlaceComment->getCommentAuthor() === $this) {
                $hoodtrooperUserPlaceComment->setCommentAuthor(null);
            }
        }

        return $this;
    }
}
