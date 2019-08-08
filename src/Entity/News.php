<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository")
 */
class News
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
    private $titreNews;

    /**
     * @ORM\Column(type="text")
     */
    private $textNews;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagNews;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="news")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreNews(): ?string
    {
        return $this->titreNews;
    }

    public function setTitreNews(string $titreNews): self
    {
        $this->titreNews = $titreNews;

        return $this;
    }

    public function getTextNews(): ?string
    {
        return $this->textNews;
    }

    public function setTextNews(string $textNews): self
    {
        $this->textNews = $textNews;

        return $this;
    }

    public function getImagNews(): ?string
    {
        return $this->imagNews;
    }

    public function setImagNews(string $imagNews): self
    {
        $this->imagNews = $imagNews;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
