<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\RetraitController;
use ApiPlatform\Core\Annotation\ApiFilter;
use App\Controller\TransactionsController;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ApiResource(
 * collectionOperations={
 *         "get"={},
 *         "post"={
 *            "controller"= TransactionsController::class,
 * }
 *                 },
 *     itemOperations={
 *         "get"={},
 *          "put"={"access_control"="is_granted('EDIT',object)",
 *  "controller"= RetraitController::class,
 * },
 *               
 *         
 *                    } ,
 * )
 * @ORM\Entity(repositoryClass="App\Repository\TransactionsRepository")
 */
class Transactions
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
    private $nomsender;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phonesender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nombenef;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $phonebenef;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ninbenef;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ApiFilter(SearchFilter::class, properties={"code": "exact"})
     */
    private $code;

    /**
     * @ORM\Column(type="date")
     */
    private $datedenvoi;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateretrait;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     */
    private $userdepot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     */
    private $userretrait;

    /**
     * @ORM\Column(type="integer")
     */
    private $montantdepot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $frais;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantnet;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAccount", inversedBy="transactions")
     */
    private $compteenvoi;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAccount", inversedBy="transactions")
     */
    private $compteretrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partetat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partsysteme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partcompteenvoi;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $partcompteretrait;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statustrans;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomsender(): ?string
    {
        return $this->nomsender;
    }

    public function setNomsender(string $nomsender): self
    {
        $this->nomsender = $nomsender;

        return $this;
    }

    public function getPhonesender(): ?int
    {
        return $this->phonesender;
    }

    public function setPhonesender(?int $phonesender): self
    {
        $this->phonesender = $phonesender;

        return $this;
    }

    public function getNombenef(): ?string
    {
        return $this->nombenef;
    }

    public function setNombenef(?string $nombenef): self
    {
        $this->nombenef = $nombenef;

        return $this;
    }

    public function getPhonebenef(): ?int
    {
        return $this->phonebenef;
    }

    public function setPhonebenef(?int $phonebenef): self
    {
        $this->phonebenef = $phonebenef;

        return $this;
    }

    public function getNinbenef(): ?int
    {
        return $this->ninbenef;
    }

    public function setNinbenef(int $ninbenef): self
    {
        $this->ninbenef = $ninbenef;

        return $this;
    }
    public function __construct()
    {

        $code = rand(10000000, 99999900);
        $this->code = $code;
        $this->datedenvoi = new \DateTime();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDatedenvoi(): ?\DateTimeInterface
    {
        return $this->datedenvoi;
    }

    public function setDatedenvoi(\DateTimeInterface $datedenvoi): self
    {
        $this->datedenvoi = $datedenvoi;

        return $this;
    }

    public function getDateretrait(): ?\DateTimeInterface
    {
        return $this->dateretrait;
    }

    public function setDateretrait(?\DateTimeInterface $dateretrait): self
    {
        $this->dateretrait = $dateretrait;

        return $this;
    }

    public function getUserdepot(): ?User
    {
        return $this->userdepot;
    }

    public function setUserdepot(?User $userdepot): self
    {
        $this->userdepot = $userdepot;

        return $this;
    }

    public function getUserretrait(): ?User
    {
        return $this->userretrait;
    }

    public function setUserretrait(?User $userretrait): self
    {
        $this->userretrait = $userretrait;

        return $this;
    }

    public function getMontantdepot(): ?int
    {
        return $this->montantdepot;
    }

    public function setMontantdepot(int $montantdepot): self
    {
        $this->montantdepot = $montantdepot;

        return $this;
    }

    public function getFrais(): ?int
    {
        return $this->frais;
    }

    public function setFrais(?int $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getMontantnet(): ?int
    {
        return $this->montantnet;
    }

    public function setMontantnet(?int $montantnet): self
    {
        $this->montantnet = $montantnet;

        return $this;
    }

    public function getCompteenvoi(): ?BankAccount
    {
        return $this->compteenvoi;
    }

    public function setCompteenvoi(?BankAccount $compteenvoi): self
    {
        $this->compteenvoi = $compteenvoi;

        return $this;
    }

    public function getCompteretrait(): ?BankAccount
    {
        return $this->compteretrait;
    }

    public function setCompteretrait(?BankAccount $compteretrait): self
    {
        $this->compteretrait = $compteretrait;

        return $this;
    }

    public function getPartetat(): ?int
    {
        return $this->partetat;
    }

    public function setPartetat(?int $partetat): self
    {
        $this->partetat = $partetat;

        return $this;
    }

    public function getPartsysteme(): ?int
    {
        return $this->partsysteme;
    }

    public function setPartsysteme(?int $partsysteme): self
    {
        $this->partsysteme = $partsysteme;

        return $this;
    }

    public function getPartcompteenvoi(): ?int
    {
        return $this->partcompteenvoi;
    }

    public function setPartcompteenvoi(?int $partcompteenvoi): self
    {
        $this->partcompteenvoi = $partcompteenvoi;

        return $this;
    }

    public function getPartcompteretrait(): ?int
    {
        return $this->partcompteretrait;
    }

    public function setPartcompteretrait(?int $partcompteretrait): self
    {
        $this->partcompteretrait = $partcompteretrait;

        return $this;
    }

    public function getStatustrans(): ?bool
    {
        return $this->statustrans;
    }

    public function setStatustrans(?bool $statustrans): self
    {
        $this->statustrans = $statustrans;

        return $this;
    }
}