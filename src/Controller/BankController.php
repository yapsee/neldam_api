<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Repository\RolesRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BankController extends AbstractController
{


    private $tokenstorage;
    private $userPasswordEncoder;
    private  $repo;
    

    public function __construct(RolesRepository $repo, UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $this->repo = $repo;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
   


    public function __invoke(BankAccount $data, TokenStorageInterface $tokenstorage): BankAccount
    {
        $this->tokenstorage = $tokenstorage;
    

        #recuperation du User connecte

        $userConn = $this->tokenstorage->getToken()->getUser();
        $data->setAdmin($userConn);

        #verifier si partenaire existe?

        $userpart = $data->getPartenaire()->getUsers()[0];
      
        $idUser = $userpart->getId();
      
      
        //recuperation password (saisi)
        $pass = $data->getPartenaire()->getUsers()[0]->getPassword();
       
        //montant premier depot
        

        if ($idUser == null) {
          $userpart->setPassword($this->userPasswordEncoder->encodePassword($userpart, $pass));
            
           
            $userpart->setRoles([$this->repo->findByLibelle("PARTNER")[0]]);
           
          
        }
        $montant = $data->getDepots()[0]->getMontant();
       

        if ($montant >= 500000) {
           
            $data->setSolde($montant);
           
         
        } else {
            throw new Exception("Le montant doit etre superieur ou égale à 500.000");
        }


        return $data;
    }
}