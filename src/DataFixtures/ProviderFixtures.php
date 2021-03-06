<?php

namespace App\DataFixtures;


use App\Entity\Logos;
use Faker\Factory;
use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ProviderFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    //injection des dépendances par le constructeur, Symfony ne nous le passera pas par la fonction load
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    const AMOUNT_CP = 6;
    const AMOUNT_LOCALITE = 3;

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        for ($k = 1; $k < 10; $k++) {

            $logo = new Logos();
            $logo->setFilename('5c7c2565eecda296921473.jpg');
            $manager->persist($logo);

            $provider = new Provider();

            $password = $this->encoder->encodePassword($provider, 'password');

            $provider->setName($faker->name);
            $provider->setEmailContact($faker->email);
            $provider->setPhone($faker->phoneNumber);
            $provider->setTva($faker->vat);
            $provider->setWeb($faker->domainName);
            $provider->setAdress($faker->streetAddress);
            $provider->setAdressNumber($faker->randomDigit);
            $randCP = rand(1,self::AMOUNT_CP);
            $provider->setCodePostal($this->getReference('cp_'.$randCP));
            $provider->setLocalite($this->getReference('localite_'.$randCP.'_'.rand(1,self::AMOUNT_LOCALITE)));
            $provider->setEmail($faker->email);
            $provider->setPhone($faker->phoneNumber);
            $provider->setPassword($password);
            $provider->setRegistration($faker->dateTimeBetween('-365 days', '-1 days'));
            $provider->setConfirmed(1);
            $provider->setAttempt(0);
            $provider->setBanished(false);
            $provider->addService($this->getReference("service_".rand(1,10)));
            $provider->addService($this->getReference("service_".rand(1,10)));
            $provider->addLogo($logo);


            $manager->persist($provider);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            LocaliteFixtures::class,
            ServicesFixtures::class,
        );

    }

}
