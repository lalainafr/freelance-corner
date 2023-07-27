<?php

namespace App\DataFixtures;

use App\Entity\Message;
use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Missions;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    // Implementer FAKER
    /**
     * @var Generator
     */
    private Generator $faker;

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $hasher;
    }


    // Mise en place des FIXTURES
    public function load(ObjectManager $manager)
    {

        //USER
        // création de l'user admin
        $admin = new User();
        $admin->setFullName('Administrateur')
            ->setEmail('admin@test.test')
            ->setPhone('01 02 03 04 05')
            ->setPassword($this->hasher->hashPassword($admin, 'Admin*123'))
            ->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $manager->persist($admin);

        // création de l'user employeur
        for ($i = 0; $i < 3; $i++) {
            $employeur = new User();
            $employeur->setFullName($this->faker->name())
                ->setEmail('Employeur' . $i . '@test.test')
                ->setPhone('01 02 03 04 05')
                ->setPassword($this->hasher->hashPassword($employeur, 'Employeur*123'))
                ->setRoles(['ROLE_EMP']);
            $usersEmp[] = $employeur;
            $manager->persist($employeur);
        }

        // création de l'user freelance
        for ($i = 0; $i < 3; $i++) {
            $freelance = new User();
            $freelance->setFullName($this->faker->name())
                ->setEmail('Freelance' . $i . '@test.test')
                ->setPhone('01 02 03 04 05')
                ->setPassword($this->hasher->hashPassword($freelance, 'Freelance*123'))
                ->setRoles(['ROLE_USER']);
            $users[] = $freelance;
            $manager->persist($freelance);
        }

        //MISSION
        $missions = [];
        for ($i = 0; $i < 10; $i++) {
            $mission = new Missions();
            $mission->setTitle('MIS - ' . $this->faker->word(10));
            $mission->setDescription($this->faker->text(100));
            $mission->setDeadline($this->faker->dateTimeBetween('+10 day', '20 days'));
            $mission->setPrice(mt_rand(400, 800));
            $mission->setCreatedAt($this->faker->dateTimeBetween('-10 day', '-2 days'));
            $mission->setUser($usersEmp[mt_rand(1, count($users) - 1)]);
            $missions[] = $mission;
            $manager->persist($mission);
        }

        //MESSAGE
        $message = [];
        for ($i = 0; $i < 10; $i++) {
            $message = new Message();
            $message->setContent($this->faker->text(100));
            $message->setUser($users[mt_rand(1, count($users) - 1)]);
            $manager->persist($message);
        }




        $manager->flush();
    }
}
