<?php
namespace App\DataFixtures;
use App\Entity\Roles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class RolesFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $libelle=array("ADMIN_SYS","ADMIN","PARTNER","CAISSIER","USER");
        
        for($i=0;$i<count($libelle);$i++){
            $roles= new Roles();
            $roles->setLibelle($libelle[$i]);
           
            $manager->persist($roles);
        }
        $manager->flush();
    }
}