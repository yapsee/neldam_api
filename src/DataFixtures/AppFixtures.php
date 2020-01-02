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
        $user = new User();
        $user->setUsername('Yapsee');
        $password = $this->encoder->encodePassword($user, 'admin');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN_SYS']);
        $user->setPrenom('Yafsa');
        $user->setNom('sy');
        $user->setIsActive(true);
        $user->setEmail('yapsasee@gmail.com');
        $manager->persist($user);
        $manager->flush();
    }
}