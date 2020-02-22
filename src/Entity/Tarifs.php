<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TarifsRepository")
 */
class Tarifs
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $montantmin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $montantmax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fees;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantmin(): ?int
    {
        return $this->montantmin;
    }

    public function setMontantmin(?string $montantmin): self
    {
        $this->montantmin = $montantmin;

        return $this;
    }

    public function getMontantmax(): ?int
    {
        return $this->montantmax;
    }

    public function setMontantmax(?int $montantmax): self
    {
        $this->montantmax = $montantmax;

        return $this;
    }

    public function getFees(): ?int
    {
        return $this->fees;
    }

    public function setFees(?int $fees): self
    {
        $this->fees = $fees;

        return $this;
    }
}