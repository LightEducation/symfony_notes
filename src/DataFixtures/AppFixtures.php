<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Command;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($p=1;$p<=10;$p++){
            $product = new Product();
            $product->setLabel(uniqid());
            $product->setPrice(mt_rand(10, 100));
            $product->setQuantity(rand(0,100));
            $manager->persist($product);
            $this->setReference("P" . $p,$product);
        }

        for($c=1;$c<=10;$c++){
            $client = new Client();
            $client->setName("Client $c");
            $client->setContact(rand(100000,999999));
            $manager->persist($client);
            $this->setReference("C$c",$client);
        }

        for($i=0;$i<10;$i++){
            $cmd = new Command();
            $client  = $this->getReference("C" . rand(1,10));
            $product = $this->getReference("P" . rand(1,10));

            $client->addCommand($cmd);
            $product->addCommand($cmd);

            $cmd->setDate(new \DateTime());
            $cmd->setQuantity(rand(1,10));
            $manager->persist($cmd);
        }

        $manager->flush();
    }
}
