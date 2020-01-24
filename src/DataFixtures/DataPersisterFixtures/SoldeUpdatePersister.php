<?php

namespace App\DataFixtures\DataPersisterFixtures;

use App\Entity\BankAccount;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Repository\DepotRepository;

class SoldeUpdatePersister implements DataPersisterInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, DepotRepository  $repo )
    {
        $this->entityManager = $entityManager;
        
        $this->$repo= $repo;
    }
    public function persist($data)
    {
           if($data->Id !== null){

               
            $depots = $this->repo->findAll();

            foreach ($depots as $depot) {
                $data->setSolde(
                    $depot->getMontant - +$depot->getBankAccount()->getSolde()
                );
                $data->eraseCredentials();

                $this->entityManager->persist($data);
                $this->entityManager->flush();
            }
    
                                  }
       else {
            $data->setNumeroCompte("");
            $data->setSolde(0);
        
    }
}
    public function supports($data): bool
    {
        return $data instanceof BankAccount;
    }
    public function remove($data)
    {

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}