<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Missions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;


class AppFixtures extends Fixture
{
    // Implementer FAKER
    /**
     * @var Generator
     */
    private Generator $faker;

    private $reservations;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    // Mise en place des FIXTURES
    public function load(ObjectManager $manager)
    {
        //MISSION
        $missions = [];
        for ($i = 0; $i < 10; $i++) {
            $mission = new Missions();
            $mission->setTitle('MIS - ' . $this->faker->word(10));
            $mission->setDescription($this->faker->text(100));
            $mission->setDeadline($this->faker->dateTimeBetween('+10 day', '20 days'));
            $mission->setPrice(mt_rand(400, 800));
            $mission->setCreatedAt($this->faker->dateTimeBetween('-10 day', '-2 days'));
            $manager->persist($mission);
         }

        $manager->flush();
    }
}

