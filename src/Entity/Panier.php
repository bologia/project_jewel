<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datePanier;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="effectue")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comporte", mappedBy="panier")
     */
    private $comportePanier;

    /**
     * @ORM\Column(type="boolean")
     */
    private $finishedPanier;

    public function __construct()
    {
        $this->comportePanier = new ArrayCollection();
        $this->finishedPanier = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePanier(): ?\DateTimeInterface
    {
        return $this->datePanier;
    }

    public function setDatePanier(?\DateTimeInterface $datePanier): self
    {
        $this->datePanier = $datePanier;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comporte[]
     */
    public function getComportePanier(): Collection
    {
        return $this->comportePanier;
    }

    public function addComportePanier(Comporte $comportePanier): self
    {
        if (!$this->comportePanier->contains($comportePanier)) {
            $this->comportePanier[] = $comportePanier;
            $comportePanier->setPanier($this);
        }

        return $this;
    }

    public function removeComportePanier(Comporte $comportePanier): self
    {
        if ($this->comportePanier->contains($comportePanier)) {
            $this->comportePanier->removeElement($comportePanier);
            // set the owning side to null (unless already changed)
            if ($comportePanier->getPanier() === $this) {
                $comportePanier->setPanier(null);
            }
        }

        return $this;
    }

    public function getFinishedPanier(): ?bool
    {
        return $this->finishedPanier;
    }

    public function setFinishedPanier(bool $finishedPanier): self
    {
        $this->finishedPanier = $finishedPanier;

        return $this;
    }
}
