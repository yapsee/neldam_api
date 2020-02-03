<?php

namespace App\DataFixtures\DataPersisterFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements DataPersisterInterface
{

    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    public function persist($data)
    { 
        
        
            $data->setPassword(
                $this->userPasswordEncoder->encodePassword($data, $data->getPassword())
            );
            $data->eraseCredentials();

        $this->entityManager->persist($data);
        $this->entityManager->flush();
        
    }
    public function supports($data): bool
    {
        return $data instanceof User;
    }
    public function remove($data)
    {

        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}