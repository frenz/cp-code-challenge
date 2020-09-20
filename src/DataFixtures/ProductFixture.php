<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getFixtureData() as $name => $stock) {
            $product = new Product($name, $stock);
            $manager->persist($product);
        }

        $manager->flush();
    }

    /**
     * @return int[]
     */
    private function getFixtureData(): array
    {
        return [
            "Iphone 11" => 100,
            "Iphone x" => 2,
            "Iphone 5" => 0,
        ];
    }
}
