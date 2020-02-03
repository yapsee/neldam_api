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




    public function __construct(RolesRepository $repo, UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $this->repo = $repo;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    private $tokenstorage;


    public function __invoke(BankAccount $data, TokenStorageInterface $tokenstorage): BankAccount
    {
        $this->tokenstorage = $tokenstorage;

        #recuperation du User connecte

        $userConn = $this->tokenstorage->getToken()->getUser();

        #verifier si partenaire existe?

        $user = $data->getPartenaire()->getUser();
        $idUser = $data->getPartenaire()->getUser()->getId();
        //recuperation password (saisi)
        $pass = $data->getPartenaire()->getUser()->getPassword();
        //montant premier depot
        $montant = ($data->getDepots()[count($data->getDepots()) - 1]->getMontant());

        if ($idUser == null) {
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $pass));
            $user->setRoles([$this->repo->findByLibelle("PARTNER")[0]]);
        }

        if ($montant >= 500000) {
            $data->setAdmin($userConn);
            $data->setSolde($montant);
        } else {
            throw new Exception("Le montant doit etre superieur ou égale à 500.000");
        }


        return $data;
    }
}