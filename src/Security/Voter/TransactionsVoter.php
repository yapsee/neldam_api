<?php

namespace App\Security\Voter;

use Exception;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TransactionsVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'POST'])
            && $subject instanceof \App\Entity\Transactions;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $etat = $subject->getStatustrans();
        if ($etat == false ){
            return true;
        }
        if ($etat == true) {
            throw new Exception("La transaction associee a ce code a deja etee effectuee! ");
        }



        

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'POST':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}