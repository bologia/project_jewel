<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"emailUser"}, message="Cet email est déjà utilisé")
 */
class User implements UserInterface
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
     * @Assert\Email(message="Cet email n'est pas valide", checkMX = true)
     */
    private $emailUser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Le mot de passe doit faire au minimum 8 caractères")
     * @Assert\EqualTo(propertyPath="confirmmdp", message="Les 2 champs ne sont pas identiques")
     */
    private $mdpUser;

    /**
     * @Assert\EqualTo(propertyPath="mdpUser", message="Les 2 champs ne sont pas identiques")
     */
    private $confirmmdp;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\News", mappedBy="user")
     */
    private $news;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users")
     */
    private $roleUser;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->roleUser = new ArrayCollection();
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

    public function getConfirmmdp(): ?string
    {
        return $this->confirmmdp;
    }

    public function setConfirmmdp(string $confirmmdp): self
    {
        $this->confirmmdp = $confirmmdp;

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

    public function getPassword()
    {
        return $this->mdpUser;
    }

    public function getUsername()
    {
        return $this->prenomUser;
    }

    public function eraseCredentials() {}

    public function getSalt() {}

    public function getRoles() {
        
        $roles = $this->roleUser->map(function($role){
            return $role->getNomRole();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoleUser(): Collection
    {
        return $this->roleUser;
    }

    public function addRoleUser(Role $roleUser): self
    {
        if (!$this->roleUser->contains($roleUser)) {
            $this->roleUser[] = $roleUser;
        }

        return $this;
    }

    public function removeRoleUser(Role $roleUser): self
    {
        if ($this->roleUser->contains($roleUser)) {
            $this->roleUser->removeElement($roleUser);
        }

        return $this;
    }
}
