<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CustoberFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $customer = new Customer;
        $customer->setName('Customer 1');
        $manager->persist($customer);

        $manager->flush();
    }
}
