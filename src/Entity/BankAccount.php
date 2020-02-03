<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\BankController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 * denormalizationContext={"groups"={"write"}},
 * collectionOperations={
 *         "get"={
 *          "normalization_context"={"groups"={"read"}}},
 *         "post"={
 * "security"="is_granted(['ROLE_ADMIN_SYS','ROLE_ADMIN'])", "security_message"="Seul ADMIN peut creer un compte partenaire"
 * ,"controller"= BankController::class}
 *     },
 * itemOperations={
 *     "get"={ 
 * "security"="is_granted('ROLE_ADMIN_SYST')"},
 *      "put"={"security"="is_granted(['ROLE_ADMIN_SYST','ROLE_ADMIN'])", "security_message"="Seul ADMIN_SYST peut modifier donneees compte partenaire "}
 * } 
 * )
 * @ORM\Entity(repositoryClass="App\Repository\BankAccountRepository")
 */
class BankAccount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @ApiFilter(SearchFilter::class, properties={"numerocompte": "exact"})
     * @Groups({"read", "write"})
     */
    private $numerocompte;

    /**
     * @ORM\Column(type="integer", length=255)
     * @Groups({"read", "write"})
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Partenaire", inversedBy="bankAccounts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $partenaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Depot", mappedBy="compte")
     * @Groups({"read", "write"})
     */
    private $depots;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bankAccounts")
     */
    private $admin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;


    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->datecreation = new \DateTime();
        $a = "NLD-";
        $b = rand(100000, 999999);
        $account = ($a . $b);
        $this->numerocompte = $account;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumerocompte(): ?string
    {
        return $this->numerocompte;
    }

    public function setNumerocompte(string $numerocompte): self
    {

        $this->numerocompte = $numerocompte;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;

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
            $depot->setCompte($this);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): self
    {
        if ($this->depots->contains($depot)) {
            $this->depots->removeElement($depot);
            // set the owning side to null (unless already changed)
            if ($depot->getCompte() === $this) {
                $depot->setCompte(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }
 
    
  

}