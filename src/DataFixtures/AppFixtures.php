<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //
        // for ($i=0; $i<=30; $i++){
        //     $product = new Product();
        //     $product->setName($faker->slug);
        //     $product->setQuantity($faker->randomDigit());
        // }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
