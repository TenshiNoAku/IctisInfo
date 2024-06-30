<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Audience;


class AppFixtures extends Fixture
{	

    public function load(ObjectManager $manager)
    {	
    	
    	
        $a = new Audience();
        $audiences = $a->load();
        foreach($audiences as $audience){
        	$manager->persist($audience);
        }
        
        
        
       $manager->flush();
    }
}
