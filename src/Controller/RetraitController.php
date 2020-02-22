<?php

namespace App\Controller;

use Twilio\Rest\Client;
use App\Entity\BankAccount;
use App\Entity\Transactions;
use App\Repository\RolesRepository;
use App\Repository\TarifsRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RetraitController extends AbstractController
{


    private $tokenstorage;
    private $userPasswordEncoder;
    private  $tarifs;


    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, TarifsRepository $tarifs)
    {


        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tarifs = $tarifs;
    }


    public function __invoke(Transactions $data, TarifsRepository $tarifs, TokenStorageInterface $tokenstorage): Transactions
    {
        $this->tokenstorage = $tokenstorage;


        #recuperation du User connecte

        $userConn = $this->tokenstorage->getToken()->getUser();

        $nin = $data->getNinbenef();

        if ($nin !== null){
        

        $data->setUserretrait($userConn);
        
        


        #query le montant de depot pour en deduire les frais a setter

        $montant =  $data->getMontantdepot();

        $recupgrille = $tarifs->findByfrais($montant);

        if ($montant >= 2000000) {
            $frais = ($montant * 0.02);
        } else {

            $frais = $recupgrille[0]->getFees();
        }

        $data->setFrais($frais);

        #setter le montant net a percevoir
        $montantnet = $montant - $frais;

        $data->setMontantnet($montantnet);

        #recuper le compte du userconn pour setter le compte qui a fait l'envoi

        $comptesender = $userConn->getAffectations()[0]->getCompte();

        $data->setCompteretrait($comptesender);

        #setter le statut a false pour dire que y'a pas encore eu de retrait

        $data->setStatustrans(true);


        #recuperation solde du compte

        $solde = $comptesender->getSolde();



            $comptesender->setSolde($solde + $montantnet);

              //APIsms avec Twilio
            $sid    = "AC14de535de509bb1df6083eecbaf7961d";
             $token  = "df257bc57f3a7e28c77b9024e640669f";
            $sender = new Client($sid, $token);
            $neldampay = "+19782375061";

            $sender->messages->create(
             '+221' . $data->getPhonesender(),

             array(
             'from' => $neldampay,
                    'body' => 'Welcome to NELDAMPAY- ' . $data->getNombenef() . 'vient de retirer les' .$montantnet.
                        'FCFA que vous lui avez envoye CODE :' . $data->getCode() .
                        'MERCI et a bientot '
                )
               ); 
 
           
    }

        return $data;
    }
}