<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ApiResource(
 *  normalizationContext={"groups"={"read"}},
 *   denormalizationContext={"groups"={"write"}})
 * @ORM\Entity(repositoryClass="App\Repository\PartenaireRepository")
 */
class Partenaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(SearchFilter::class, properties={"ninea": "exact"})
     */
    private $ninea;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="string", length=255)
    
     */
    private $regicomm;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BankAccount", mappedBy="partenaire", orphanRemoval=true,cascade={"persist"})
     */
    private $bankAccounts;

     /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="partenaire",cascade={"persist"})
    */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Contrat", cascade={"persist", "remove"})
     */
    private $contrat;

    public function __construct()
    {
        $this->bankAccounts = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNinea(): ?string
    {
        return $this->ninea;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;

        return $this;
    }

    public function getRegicomm(): ?string
    {
        return $this->regicomm;
    }

    public function setRegicomm(string $regicomm): self
    {
        $this->regicomm = $regicomm;

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
            $bankAccount->setPartenaire($this);
        }

        return $this;
    }

    public function removeBankAccount(BankAccount $bankAccount): self
    {
        if ($this->bankAccounts->contains($bankAccount)) {
            $this->bankAccounts->removeElement($bankAccount);
            // set the owning side to null (unless already changed)
            if ($bankAccount->getPartenaire() === $this) {
                $bankAccount->setPartenaire(null);
            }
        }

        return $this;
    }

  

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPartenaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPartenaire() === $this) {
                $user->setPartenaire(null);
            }
        }

        return $this;
    }

    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

    public function setContrat(?Contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }
}