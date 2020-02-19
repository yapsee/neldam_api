<?php

namespace App\Controller;


use App\Entity\Affectation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AffectationController extends AbstractController
{


    private $tokenstorage;



    public function __construct(TokenStorageInterface $tokenstorage)
    {


        $this->tokenstorage = $tokenstorage;
    }



    public function __invoke(Affectation $data): Affectation
    {


        #recuperation du User connecte pour setter celui qui s'est connecte
        #et qui voudrait affecter

        $userConn = $this->tokenstorage->getToken()->getUser();
        $data->setAffectedby($userConn);

        return $data;
    }
}