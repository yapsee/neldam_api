<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 * collectionOperations={
 *         "get"={},
 *         "post"={"access_control"="is_granted('ADD',object)"},
 *                      },
 *     itemOperations={
 *         "get"={"access_control"="is_granted('GET',object)"},
 *               }
 * 
 *  )
 * @ORM\Entity(repositoryClass="App\Repository\DepotRepository")
 */
class Depot
{
    /**
     * @Groups({"read"})
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datedepot;

    /**
     * @Groups({"read", "write"})
     * @ORM\Column(type="integer", length=255)
    
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAccount", inversedBy="depots")
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="depots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $caissier;



    public function getId(): ?int
    {
        return $this->id;
    }
    public function __construct()
    {
        $this->datedepot = new \DateTime();
    }

    public function getDatedepot(): ?\DateTimeInterface
    {
        return $this->datedepot;
    }

    public function setDatedepot(\DateTimeInterface $datedepot): self
    {

        $this->datedepot = $datedepot;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCompte(): ?BankAccount
    {
        return $this->compte;
    }

    public function setCompte(?BankAccount $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getCaissier(): ?User
    {
        return $this->caissier;
    }

    public function setCaissier(?User $caissier): self
    {
        $this->caissier = $caissier;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}