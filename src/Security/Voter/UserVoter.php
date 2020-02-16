<?php

namespace App\Security\Voter;

use Exception;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserVoter extends Voter
{
    const ROLE_ADMIN_SYS = 'ROLE_ADMIN_SYS';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_PARTNER = 'ROLE_PARTNER';
    const ROLE_ADMIN_PARTN = 'ROLE_ADMIN_PARTN';
    const ROLE_USER_PARTN = 'ROLE_USER_PARTN ';
    const ROLE_CAISSIER = 'ROLE_CAISSIER';
    private $decisionManager;
    private $tokenStorage;
    private $security;

    public function __construct(Security $security, AccessDecisionManagerInterface $decisionManager, TokenStorageInterface $tokenStorage)
    {
        $this->decisionManager = $decisionManager;
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'ADD', 'GET'])
            && $subject instanceof \App\Entity\User;
    }
    /** @var User $subject */
    /** @param User $subject */

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, array(self::ROLE_ADMIN_SYS))) {
            return true;
        }
        $userConn = $this->tokenStorage->getToken();
        if ($userConn->getRoles()[0] == self::ROLE_CAISSIER || $userConn->getRoles()[0] == self::ROLE_USER_PARTN) {
  
                throw new Exception ("UN CAISSIER OU UN USER PARTENAIRE NE PEUT PAS CREER OU MODIFIER UN USER") ;
            }
        if (($userConn->getRoles()[0] == self::ROLE_ADMIN) && ($subject->getRoles()[0] == self::ROLE_ADMIN_SYS 
        || $subject->getRoles()[0] == self::ROLE_ADMIN)) {

            throw new Exception("UN ADMIN NE PEUT PAS CREER UN ADMIN SYST OU UN ADMIN ");
            }
        if (($userConn->getRoles()[0] == self::ROLE_ADMIN) && ($subject->getRoles()[0] == self::ROLE_ADMIN_PARTN
            || $subject->getRoles()[0] == self::ROLE_PARTNER || $subject->getRoles()[0] == self::ROLE_USER_PARTN)) {

           return true;
        }
        if (($userConn->getRoles()[0] == self::ROLE_PARTNER ) && ($subject->getRoles()[0] == self::ROLE_USER_PARTN
            || $subject->getRoles()[0] == self::ROLE_ADMIN_PARTN)) {

            return true;
        }
        if ($userConn->getRoles()[0] == self::ROLE_ADMIN_PARTN && $subject->getRoles()[0] == self::ROLE_USER_PARTN) {

            return true;
        }
        
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
               
                break;
            case 'ADD':
               
                break;
            case 'GET':
               
              break;
            
        }

        throw new Exception(sprintf('Vous n\'avez pas le droit!', $attribute));
    }
}