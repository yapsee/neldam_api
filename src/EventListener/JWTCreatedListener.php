<?php

// src/App/EventListener/JWTCreatedListener.php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Exception\DisabledException;

class JWTCreatedListener
{

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();
        //blocage d'un user
        if(!$user->getIsactive()){
            throw new DisabledException("Vous etes temporairement bloque!");
        }
        //BLoquer partenaire et ses users
        elseif($user->getPartenaire() !== null && !$user->getPartenaire()->getUsers()[0]->getIsactive()){

            throw new DisabledException("Veuillez vous rapprocher aupres de votre agence!");
        }

        // merge with existing event data
        $payload = array_merge(
            $event->getData(),
            [
                'password' => $user->getPassword()
            ]
        );

        $event->setData($payload);
    }
}