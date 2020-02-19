<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\AffectationController;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 * collectionOperations={
 *         "get"={},
 *         "post"={"access_control"="is_granted('ADD',object)",
 *            "controller"= AffectationController::class,
 * }
 *                 },
 *     itemOperations={
 *         "get"={},
 *          "put"={"access_control"="is_granted('EDIT',object)"},
 *         
 *                    })
 * @ORM\Entity(repositoryClass="App\Repository\AffectationRepository")
 */
class Affectation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datedebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datefin;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $affectedto;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BankAccount", inversedBy="affectations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $compte;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="affectation")
     */
    private $affectedby;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(?\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getAffectedto(): ?User
    {
        return $this->affectedto;
    }

    public function setAffectedto(?User $affectedto): self
    {
        $this->affectedto = $affectedto;

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

    public function getAffectedby(): ?User
    {
        return $this->affectedby;
    }

    public function setAffectedby(?User $affectedby): self
    {
        $this->affectedby = $affectedby;

        return $this;
    }
}