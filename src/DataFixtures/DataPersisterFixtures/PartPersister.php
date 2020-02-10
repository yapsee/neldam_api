<?php

namespace App\DataFixtures\DataPersisterFixtures;

use App\Entity\BankAccount;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PartPersister implements DataPersisterInterface
{
    #Persisting balance of an account been on it 2days

    private $entityManager;
    private  $tokenstorage;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder, TokenStorageInterface $tokenstorage)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenstorage = $tokenstorage;
    }

    public function supports($data): bool
    {
        return $data instanceof BankAccount;
    }
    public function persist($data)
    {
     
        #verifier si partenaire existe?

        $partner = $data->getPartenaire()->getUsers()[0];
        $idUser = $data->getPartenaire()->getUsers()[0]->getId();
        //recuperation password (saisi)
        $pass = $partner->getPassword();
        $userConn = $this->tokenstorage->getToken()->getUser();
        //montant premier depot

        if ($idUser == null) {
            $partner->setPassword($this->userPasswordEncoder->encodePassword($partner, $pass));

            $data->getDepots()[0]->setCaissier($userConn);
            $data->setAdmin($userConn);
            $data->eraseCredentials();

            $this->entityManager->persist($data);
            $this->entityManager->flush();
        } else {
            throw new Exception;
        }
    }

    public function remove($data)
    {

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}