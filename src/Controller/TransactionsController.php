<?php

namespace App\Controller;

use App\Osms;

use Twilio\Rest\Client;
use App\Entity\BankAccount;
use App\Entity\Transactions;
use App\Repository\RolesRepository;
use App\Repository\TarifsRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TransactionsController extends AbstractController
{


    private $tokenstorage;
    private $userPasswordEncoder;
    private  $tarifs;


    public function __construct( UserPasswordEncoderInterface $userPasswordEncoder, TarifsRepository $tarifs)
    {

  
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->tarifs = $tarifs;
    }
    

  public function __invoke(Transactions $data, TarifsRepository $tarifs, TokenStorageInterface $tokenstorage): Transactions
    {
        $this->tokenstorage = $tokenstorage;


        #recuperation du User connecte

        $userConn = $this->tokenstorage->getToken()->getUser();
        
                    $data->setUserdepot($userConn);
        
        
        #query le montant de depot pour en deduire les frais a setter
        
        
        
        $montant =  $data->getMontantdepot();
      
        $recupgrille = $tarifs->findByfrais($montant);
        
        if($montant>=2000000){
            $frais = ($montant * 0.02);
            
        }
        else{
      
         $frais = $recupgrille[0]->getFees();
         
        }
        //Empecher le user d'utiliser son compte apres affectation
        $jour = new \DateTime();

        $firstday =  $userConn->getAffectations()[0]->getDatedebut();
        $lastday = $userConn->getAffectations()[0]->getDatefin();
        if ($firstday > $jour || $jour > $lastday) {
            throw new Exception("Vous n'etes affilie a aucun compte");
        }
         
      $data->setFrais($frais);

        #setter le montant net a percevoir
       $montantnet = $montant - $frais;

       $data->setMontantnet($montantnet);

        #recuper le compte du userconn pour setter le compte qui a fait l'envoi

        $comptesender = $userConn->getAffectations()[0]->getCompte();
      
        $data->setCompteenvoi($comptesender);
        
        #setter le statut a false pour dire que y'a pas encore eu de retrait
        
        $data->setStatustrans(false);
        
        #parts des entites
        $partetat = $frais *0.4;
        $partsyst = $frais * 0.3;
        $partdepot = $frais * 0.1;
        $partdenvoi = $frais * 0.2;
        
           $data->setPartetat($partetat);
           $data->setPartsysteme($partsyst);
           $data->setPartcompteenvoi($partdepot);
           $data->setPartcompteretrait($partdenvoi);
    
     

        #recuperation solde du compte

        $solde=$comptesender->getSolde();
       

        if ($montantnet < $solde){

        $comptesender->setSolde($solde- $montantnet );

            //API SMS AVEC ORANGE

            $config = array(
                'clientId' => '5y49xsv7zkGK8cZj6J4IM4c5j2FNOBhA',

                'clientSecret' => 'qzgeEnI26NduRdKP'
            );

            $osms = new Osms($config);

            // retrieve an access token
            $response = $osms->getTokenFromConsumerKey();

            if (!empty($response['access_token'])) {
                $senderAddress = 'tel:+221'. $data->getPhonesender();
                $receiverAddress = 'tel:+221'. $data->getPhonebenef();
                $message = 'Welcome to NELDAMPAY- ' . $data->getNomsender() . 'vient de vous envoyer' . $montantnet .
                    'FCFA CODE :' . $data->getCode() .
                     'Merci d\'utiliser nos services';
                $senderName = 'NELDAMPAY';

                $osms->sendSMS($senderAddress, $receiverAddress, $message, $senderName);
            } else {
                // error
            }
       return $data;
    }

       else {
           throw new Exception(" Votre compte n'est pas en mesure de faire cette operation");
       }

     
      
        }
    }