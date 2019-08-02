<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\News;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++){
            $news = new News();
            $news->setTitreNews("titre new $i")
                 ->setTextNews("content de la news $i")
                 ->setImagNews("imag/bague3.jpg")
                 ->setCreatedAt(new \DateTime());
                 
            $manager->persist($news);
        }

        $manager->flush();
    }
}
