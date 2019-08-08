<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
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
    private $nomUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenomUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $emailUser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mdpUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $roleUser;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="user")
     */
    private $news;

    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUser(): ?string
    {
        return $this->nomUser;
    }

    public function setNomUser(string $nomUser): self
    {
        $this->nomUser = $nomUser;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenomUser;
    }

    public function setPrenomUser(string $prenomUser): self
    {
        $this->prenomUser = $prenomUser;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): self
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getMdpUser(): ?string
    {
        return $this->mdpUser;
    }

    public function setMdpUser(string $mdpUser): self
    {
        $this->mdpUser = $mdpUser;

        return $this;
    }

    public function getRoleUser(): ?int
    {
        return $this->roleUser;
    }

    public function setRoleUser(int $roleUser): self
    {
        $this->roleUser = $roleUser;

        return $this;
    }

    /**
     * @return Collection|News[]
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    public function addNews(News $news): self
    {
        if (!$this->news->contains($news)) {
            $this->news[] = $news;
            $news->setUser($this);
        }

        return $this;
    }

    public function removeNews(News $news): self
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
            // set the owning side to null (unless already changed)
            if ($news->getUser() === $this) {
                $news->setUser(null);
            }
        }

        return $this;
    }
}
