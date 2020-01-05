<?php

namespace App\DataFixtures;

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
        $user1->setPrenom('Yafsa');
        $user1->setNom('sy');
        $user1->setIsActive(true);
        $user1->setEmail('yapsasee@gmail.com');
        $manager->persist($user1);
        $manager->flush();

        $user2 = new User('admin');
        $user2->setUsername('Diye');
        $password = $this->encoder->encodePassword($user2, 'admin');
        $user2->setPassword($password);
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setPrenom('fatima');
        $user2->setNom('sy');
        $user2->setIsActive(true);
        $user2->setEmail('fatima@nldm.com');
        $manager->persist($user2);

        $user3= new User('caissier');
        $user3->setUsername('Doumaket');
        $password = $this->encoder->encodePassword($user3, 'caissier');
        $user3->setPassword($password);
        $user3->setRoles(['ROLE_CAISSIER']);
        $user3->setPrenom('abdou');
        $user3->setNom('samba');
        $user3->setIsActive(true);
        $user3->setEmail('abdou@nldm.com');
        $manager->persist($user3);
        $manager->flush();
    }
}