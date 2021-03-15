<?php


namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setName($faker->lastName);
            $user->setFirstname($faker->firstName);
            $user->setUsername($faker->unique()->userName);
            $user->setMail($faker->unique()->email);
            $user->setPhone($faker->e164PhoneNumber);

            $password = $this->encoder->encodePassword($user, "123456");
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();

    }
}