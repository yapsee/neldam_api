<?php

namespace App\Security\Voter;

use App\Entity\Depot;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DepotVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ADD', 'GET'])
            && $subject instanceof \App\Entity\Depot;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $role = $user->getRole()->getLibelle();
        if ($role == "ADMIN_SYS" || $role == "ADMIN" || $role == "CAISSIER") {
            return true;
        }
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'ADD':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'GET':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

       throw new Exception("Vous n'avez pas le droit d\'effectuer un depot sur le systeme");
    }
}