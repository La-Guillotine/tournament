<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\League;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setEmail("mazeau@mazeau.fr")
            ->setTel("0557889996")
            ->setFirstName('Alexandre')
            ->setLastName("Mazeau")
            ->setIsVerified(1)
        ;
        $user->setPassword(
            $this->encoder->encodePassword(
                $user,
                "mazeau"
            )
        );
        $manager->persist($user);

        $manager->flush();
    }

    public function loadLeagues(ObjectManager $manager): void
    {
        $league = (new League())
            ->setName("Ligue de Football d'Occitanie")
            ->setWebsite("https://occitanie.fff.fr/")
            ->setAddress("238 Avenue des Cerisiers, 31560 Toulouse")
            ->setResponsible()
            ;

        $manager->persist($league);

        $league = (new League())
            ->setName("Ligue de Football de Bretagne")
            ->setWebsite("https://footbretagne.fff.fr/")
            ->setAddress("15 impasse des Vents, 29000 Quimper")
            ->setResponsible()
            ;
        
        $manager->persist($league);

        $league = (new League())
            ->setName("Ligue de Football de Normandie")
            ->setWebsite("https://normandie.fff.fr/")
            ->setAddress("56 rue Binaud, 14000 Caen")
            ->setResponsible()
            ;
        
        $manager->persist($league);

        $league = (new League())
            ->setName("Ligue de Football de Corse")
            ->setWebsite("https://corse.fff.fr/")
            ->setAddress("148 rue de la plage, 20584 Ajaccio")
            ->setResponsible()
            ;
        
        $manager->persist($league);

        $manager->flush();
        
            
    }
}
