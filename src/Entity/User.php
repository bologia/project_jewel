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
 * @UniqueEntity(fields={"emailUser"}, message="Cet email est déjà utilisé", groups={"inscription"})
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
     * @Assert\Email(message="Cet email n'est pas valide", groups={"inscription"})
     */
    private $emailUser;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Le mot de passe doit faire au minimum 8 caractères", groups={"inscription"})
     * @Assert\EqualTo(propertyPath="confirmmdp", message="Les 2 champs ne sont pas identiques", groups={"inscription"})
     */
    private $mdpUser;

    /**
     * @Assert\EqualTo(propertyPath="mdpUser", message="Les 2 champs ne sont pas identiques", groups={"inscription"})
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panier", mappedBy="user")
     */
    private $effectue;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Regex(pattern="/^[0-9]+$/", message="Chiffre seulement")
     * @Assert\Length(max=10, maxMessage="Format invalide ({{ limit }} chiffres maximum)")
     */
    private $telUser;

    public function __construct()
    {
        $this->news = new ArrayCollection();
        $this->roleUser = new ArrayCollection();
        $this->effectue = new ArrayCollection();
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

    public function getTelUser(): ?string
    {
        return $this->telUser;
    }

    public function setTelUser(string $telUser): self
    {
        $this->telUser = $telUser;

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

    /**
     * @return Collection|Panier[]
     */
    public function getEffectue(): Collection
    {
        return $this->effectue;
    }

    /**
     * Fonction qui teste si un produit est dans le panier courant
     */
    public function getAjoutPan(Produit $produit)
    {
        foreach($this->effectue as $panier){ // Parcours tous les paniers de l'utilisateur
            if($panier->getDatePanier() == null){ // si la date du panier est null c'est que c'est le panier courant
                foreach($panier->getComportePanier() as $comporte){ // Parcours tous les 'comporte' de l'utilisateur
                    if($comporte->getProduit() == $produit){ // si dans ton comporte, il y a le produit
                        return true; // Donc c'est good
                    }
                }
            }
        }
        return false; // Sinon pas trouvé
    }

    public function addEffectue(Panier $effectue): self
    {
        if (!$this->effectue->contains($effectue)) {
            $this->effectue[] = $effectue;
            $effectue->setUser($this);
        }

        return $this;
    }

    public function removeEffectue(Panier $effectue): self
    {
        if ($this->effectue->contains($effectue)) {
            $this->effectue->removeElement($effectue);
            // set the owning side to null (unless already changed)
            if ($effectue->getUser() === $this) {
                $effectue->setUser(null);
            }
        }

        return $this;
    }

}
