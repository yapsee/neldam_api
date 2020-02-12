<?php

namespace App\DataFixtures\DataPersisterFixtures;

use App\Entity\BankAccount;
use App\Repository\ContratRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PartPersister implements DataPersisterInterface
{
    #Persisting balance of an account been on it 2days

    private $entityManager;
    private  $tokenstorage;
    private  $repo;
    

    public function __construct(EntityManagerInterface $entityManager,ContratRepository $repo, UserPasswordEncoderInterface $userPasswordEncoder, TokenStorageInterface $tokenstorage)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tokenstorage = $tokenstorage;
        $this->repo= $repo;
    }

    public function supports($data): bool
    {
        return $data instanceof BankAccount;
    }
    public function persist($data)
    {
        #verifier si partenaire existe pour lui passer le contrat au moment de la creation de compte

        $userpart = $data->getPartenaire()->getUsers()[0];

        $idUser = $userpart->getId();
        
      //Personnaliser contrat
      $contrats = $this->repo->findAll();
      $terme =  $contrats[0]->getTerme();
     
     
      $nompart = $data->getPartenaire()->getUsers()[0]->getNom();
      
      $ninea =  $data->getPartenaire()->getNinea();
      $compte= $data->getNumerocompte();
                  $search = ['#nom', '#ninea', '#compte'];
                  $replace = [$nompart, $ninea,$compte];
      $termefinale = str_replace($search, $replace, $terme);
          

            $this->entityManager->persist($data);
            $this->entityManager->flush();
        if ($idUser == null) {
            return new JsonResponse ($termefinale);
        } //retoune contrat si compte nouveau
        
    }

    public function remove($data)
    {

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}