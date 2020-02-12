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
     
        #verifier si partenaire existe?

        $partner = $data->getPartenaire()->getUsers()[0];
        $idUser = $data->getPartenaire()->getUsers()[0]->getId();
        //recuperation password (saisi)
        $pass = $partner->getPassword();
        $userConn = $this->tokenstorage->getToken()->getUser();
      //generer un contrat
      $contrats = $this->repo->findAll();
      $terme =  $contrats[0]->getTerme();
      //Personnaliser contrat
     
      $nompart = $data->getPartenaire()->getUsers()[0]->getNom();
      
      $ninea =  $data->getPartenaire()->getNinea();
      $compte= $data->getNumerocompte();
                  $search = ['#nom', '#ninea', '#compte'];
                  $replace = [$nompart, $ninea,$compte];
      $termefinale = str_replace($search, $replace, $terme);
          
      
  //montant premier depot

        if ($idUser == null) {
            $partner->setPassword($this->userPasswordEncoder->encodePassword($partner, $pass));

            $data->getDepots()[0]->setCaissier($userConn);
            $data->setAdmin($userConn);
            $data->eraseCredentials();

            $this->entityManager->persist($data);
            $this->entityManager->flush();
            return new JsonResponse ($termefinale);
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