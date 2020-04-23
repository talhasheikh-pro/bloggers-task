<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Blogger;

class Bloggers extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($index=1; $index<11; $index++){
          $blogger = new Blogger();
          $blogger->setName("mickey-{$index}");
          $blogger->setEmail("mickey-{$index}@gmail.com");
          $blogger->setActive(rand(0,1));
          $blogger->setRating(rand(0,10));
          $blogger->setUsername("mickey-{$index}");
          $manager->persist($blogger);
        }

        $manager->flush();
    }
}
