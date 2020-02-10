<?php

namespace App\DataFixtures;

use App\Entity\BankAccount;
use App\Entity\Depot;
use App\Entity\Partenaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $user1 = new User('adminsys');
        $user1->setUsername('Yapsee');
        $password = $this->encoder->encodePassword($user1, 'adminsys');
        $user1->setPassword($password);
        $user1->setRoles(['ROLE_ADMIN_SYS']);
        $user1->setNom('sy');
        $user1->setIsActive(true);
        $user1->setEmail('yapsee@nldm.com');
        $manager->persist($user1);

        $user2 = new User('admin');
        $user2->setUsername('Diye');
        $password = $this->encoder->encodePassword($user2, 'admin');
        $user2->setPassword($password);
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setNom('sy');
        $user2->setIsActive(true);
        $user2->setEmail('fatima@nldm.com');
        $manager->persist($user2);

        $user4 = new User('caissier');
        $user4->setUsername('fa');
        $password = $this->encoder->encodePassword($user4, 'fa2020');
        $user4->setPassword($password);
        $user4->setRoles(['ROLE_CAISSIER']);
        $user4->setNom('FA SAMB');
        $user4->setIsActive(true);
        $user4->setEmail('fa@nldm.com');
        $manager->persist($user4);
        
        $partenaire1 =new Partenaire();
        $partenaire1->setNinea('NINEA12333');
        $partenaire1->setRegicomm('DKR00042');
        $manager->persist($partenaire1);
        
        $user3 = new User('Partner');
        $user3->setUsername('EDK');
        $password = $this->encoder->encodePassword($user3, 'edk2020');
        $user3->setPassword($password);
        $user3->setRoles(['ROLE_PARTNER']);
        $user3->setNom('EDK SARL');
        $user3->setIsActive(true);
        $user3->setPartenaire($partenaire1);
        $user3->setEmail('edk@nldm.com');
        $manager->persist($user3);
        
        $bankaccount1 = new BankAccount();
        $bankaccount1->setSolde('0');
        $bankaccount1->setPartenaire($partenaire1);
        $bankaccount1->setAdmin($user1);
        $manager->persist($bankaccount1);
        
        $depot1 = new Depot();
        $depot1->setMontant(500000);
        $depot1->setDatedepot(new \DateTime);
        $depot1->setCaissier($user4);
        $depot1->setCompte($bankaccount1);
        $manager->persist($depot1);
        
        
        $manager->flush();
    }
}