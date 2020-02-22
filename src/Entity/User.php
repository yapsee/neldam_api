<?php

namespace App\Entity;

use App\Entity\Depot;
use App\Entity\BankAccount;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\ImageController;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(
 * collectionOperations={
 *         "get"={},
 *         "post"={"access_control"="is_granted('ADD',object)"}
 *                      },
 *     itemOperations={
 *         "get"={"access_control"="is_granted('GET',object)"},
 *         "patch"={"access_control"="is_granted('EDIT',object)",
 *  "controller"=ImageController::class,
 *             "deserialize"=false,
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"}
 *                    }
 *       }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *  )
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Groups({"read", "write"})
     * @var string The hashed password)
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    public function __construct($username)
    {
        $this->IsActive = true;
        $this->username = $username;
        $this->depots = new ArrayCollection();
        $this->bankAccounts = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->affecation = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }

    /**
     * @Groups({"read", "write"})
     * @ORM\ManyToOne(targetEntity="App\Entity\Roles", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="caissier",cascade={"persist"})
     */
    private $depots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BankAccount", mappedBy="admin",cascade={"persist"})
     */
    private $bankAccounts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="users",cascade={"persist"})
     */
    private $partenaire;

    /**

     * @ORM\Column(type="blob", nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="affectedto")
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Affectation", mappedBy="affectedby")
     */
    private $affecation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="userdepot")
     */
    private $transactions;


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
        $this->roles ='ROLE_'.strtoupper($this->role->getLibelle());
        return array($this->roles);
        // guarantee every user at least has ROLE_USER

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
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getRole(): ?Roles
    {
        return $this->role;
    }

    public function setRole(?Roles $role): self
    {
        $this->role = $role;

        return $this;
    }
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @return Collection|Depot[]
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): self
    {
        if (!$this->depots->contains($depot)) {
            $this->depots[] = $depot;
            $depot->setCaissier($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCaissier() === $this) {
                $depot->setCaissier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BankAccount[]
     */
    public function getBankAccounts(): Collection
    {
        return $this->bankAccounts;
    }

    public function addBankAccount(BankAccount $bankAccount): self
    {
        if (!$this->bankAccounts->contains($bankAccount)) {
            $this->bankAccounts[] = $bankAccount;
            $bankAccount->setAdmin($this);
        }

        return $this;
    }

    public function removeBankAccount(BankAccount $bankAccount): self
    {
        if ($this->bankAccounts->contains($bankAccount)) {
            $this->bankAccounts->removeElement($bankAccount);
            // set the owning side to null (unless already changed)
            if ($bankAccount->getAdmin() === $this) {
                $bankAccount->setAdmin(null);
            }
        }

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setAffectedto($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getAffectedto() === $this) {
                $affectation->setAffectedto(null);
            }
        }

        return $this;
    }
//relation entre affectation et celui qui affecte affectedby dans affectation
    /**
     * @return Collection|Affectation[]
     */
    public function getAffecation(): Collection
    {
        return $this->affecation;
    }

    public function addAffecation(Affectation $affecation): self
    {
        if (!$this->affecation->contains($affecation)) {
            $this->affecation[] = $affecation;
            $affecation->setAffectedby($this);
        }

        return $this;
    }

    public function removeAffecation(Affectation $affecation): self
    {
        if ($this->affecation->contains($affecation)) {
            $this->affecation->removeElement($affecation);
            // set the owning side to null (unless already changed)
            if ($affecation->getAffectedby() === $this) {
                $affecation->setAffectedby(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setUserdepot($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getUserdepot() === $this) {
                $transaction->setUserdepot(null);
            }
        }

        return $this;
    }
}