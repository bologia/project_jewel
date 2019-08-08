<?php

namespace App\Entity;

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
}
