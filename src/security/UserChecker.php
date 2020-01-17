<?php

namespace App\security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

 
#autre methode pour gerer le blocage des users but you did it by AdvancedUserInterface
#in the usy entity
class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user->getIsActive()) {
            
            throw new LockedException("ce membre n'est pas actif");
                    }
    }
 
    public function checkPostAuth(UserInterface $user)
    {
 
     if (!$user->getIsActive()) {
            throw new LockedException("ce membre n'est pas actif");
        }
    }
}