<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        for ($i = 1 ; $i <= 12; $i++) {
            $alphabet="abcdefghijklmnopqrstuvwxyz";
            $lettre_aleatoire=$alphabet[rand(0,25)];
            $product = new Product();
            $product->setNom($lettre_aleatoire.' menu '.$i);
            $product->setDescription('Description du Produit '.$i);
            $product->setPrix(mt_rand(10, 100));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
