<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
 */
class Produit
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
    private $nomProduit;

    /**
     * @ORM\Column(type="float")
     */
    private $prixProduit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagProduit;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptionProduit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categorie", mappedBy="produit")
     */
    private $categorieProduit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Marque", inversedBy="produits")
     */
    private $marque;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comporte", mappedBy="produit")
     */
    private $comporteProduit;

    public function __construct()
    {
        $this->categorieProduit = new ArrayCollection();
        $this->comporteProduit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): self
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    public function getImagProduit(): ?string
    {
        return $this->imagProduit;
    }

    public function setImagProduit(string $imagProduit): self
    {
        $this->imagProduit = $imagProduit;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->descriptionProduit;
    }

    public function setDescriptionProduit(?string $descriptionProduit): self
    {
        $this->descriptionProduit = $descriptionProduit;

        return $this;
    }

    /**
     * @return Collection|Categorie[]
     */
    public function getCategorieProduit(): Collection
    {
        return $this->categorieProduit;
    }

    public function addCategorieProduit(Categorie $categorieProduit): self
    {
        if (!$this->categorieProduit->contains($categorieProduit)) {
            $this->categorieProduit[] = $categorieProduit;
            $categorieProduit->addProduit($this);
        }

        return $this;
    }

    public function removeCategorieProduit(Categorie $categorieProduit): self
    {
        if ($this->categorieProduit->contains($categorieProduit)) {
            $this->categorieProduit->removeElement($categorieProduit);
            $categorieProduit->removeProduit($this);
        }

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return Collection|Comporte[]
     */
    public function getComporteProduit(): Collection
    {
        return $this->comporteProduit;
    }

    public function addComporteProduit(Comporte $comporteProduit): self
    {
        if (!$this->comporteProduit->contains($comporteProduit)) {
            $this->comporteProduit[] = $comporteProduit;
            $comporteProduit->setProduit($this);
        }

        return $this;
    }

    public function removeComporteProduit(Comporte $comporteProduit): self
    {
        if ($this->comporteProduit->contains($comporteProduit)) {
            $this->comporteProduit->removeElement($comporteProduit);
            // set the owning side to null (unless already changed)
            if ($comporteProduit->getProduit() === $this) {
                $comporteProduit->setProduit(null);
            }
        }

        return $this;
    }
}
