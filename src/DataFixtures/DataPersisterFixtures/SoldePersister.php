<?php

namespace App\DataFixtures\DataPersisterFixtures;

use App\Entity\Depot;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SoldePersister implements DataPersisterInterface
{
#Persisting balance of an account been on it 2days

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenstorage)
    {
        $this->entityManager = $entityManager;
        $this->tokenstorage= $tokenstorage;
    }

    public function supports($data): bool
    {
        return $data instanceof Depot;
    }
    public function persist($data)
    {
            $deposit=$data->getMontant();
            $balance = $data->getCompte()->getSolde();
        $userConn = $this->tokenstorage->getToken()->getUser();
            
            if($deposit >500){
           $data->getCompte()->setSolde($deposit+ $balance);
            $data->setCaissier($userConn);
            
                
            $data->eraseCredentials();

                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
            else{
            throw new Exception("Le montant doit etre superieur ou égale à 500");
            }
             
   }

    public function remove($data)
    {

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}

 