<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\User;
use App\Entity\League;
use App\Entity\Stadium;
use App\Service\FileUploader;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;
    private FileUploader $fileUploader;
    private Finder $finder;
    private $fixturesDirectory;

    public function __construct(UserPasswordEncoderInterface $encoder, FileUploader $fileUploader, $fixturesDirectory)
    {
        $this->encoder = $encoder;
        $this->fileUploader = $fileUploader;
        $this->finder = new Finder();
        $this->fixturesDirectory = $fixturesDirectory;
    }

    public function load(ObjectManager $manager)
    {
        /**
         *  Ajout des Utilisateurs (dont l'Admin)
         */
        
        $userAdmin = (new User())
            ->setEmail("admin@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Lucas')
            ->setLastName("Guillotin")
            ->setRoles(["ROLE_ADMIN"])
            ->setIsVerified(1)
        ;
        $userAdmin->setPassword(
            $this->encoder->encodePassword(
                $userAdmin,
                "admin"
            )
        );
        $manager->persist($userAdmin);

        // Responsable Nouvelle Aquitaine
        $userNA = (new User())
            ->setEmail("responsible.nouvelleaquitaine@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Enzo')
            ->setLastName("Fonteneau")
            ->setIsVerified(1)
        ;
        $userNA->setPassword(
            $this->encoder->encodePassword(
                $userNA,
                "nouvelleaquitaine"
            )
        );
        $manager->persist($userNA);

        // Responsable Occitanie
        $userOcc = (new User())
            ->setEmail("responsible.occitanie@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Alexandre')
            ->setLastName("Mazeau")
            ->setIsVerified(1)
        ;
        $userOcc->setPassword(
            $this->encoder->encodePassword(
                $userOcc,
                "occitanie"
            )
        );
        $manager->persist($userOcc);

        // Responsable Bretagne
        $userBre = (new User())
            ->setEmail("responsible.bretagne@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Nicolas')
            ->setLastName("Vanexem")
            ->setIsVerified(1)
        ;
        $userBre->setPassword(
            $this->encoder->encodePassword(
                $userBre,
                "bretagne"
            )
        );
        $manager->persist($userBre);

        // Responsable Normandie
        $userNor = (new User())
            ->setEmail("responsible.normandie@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Marvyn')
            ->setLastName("Aboulicam")
            ->setIsVerified(1)
        ;
        $userNor->setPassword(
            $this->encoder->encodePassword(
                $userNor,
                "normandie"
            )
        );
        $manager->persist($userNor);

        // Responsable Corse
        $userCor = (new User())
            ->setEmail("responsible.corse@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Antoine')
            ->setLastName("Bidaud")
            ->setIsVerified(1)
        ;
        $userCor->setPassword(
            $this->encoder->encodePassword(
                $userCor,
                "corse"
            )
        );
        $manager->persist($userCor);

        // Secrétaire Girondins de Bordeaux
        $userFCGB = (new User())
            ->setEmail("secretary.girondinsdebordeaux@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Quentin')
            ->setLastName("Gans")
            ->setIsVerified(1)
        ;
        $userFCGB->setPassword(
            $this->encoder->encodePassword(
                $userFCGB,
                "girondinsdebordeaux"
            )
        );
        $manager->persist($userFCGB);

        // Secrétaire Chamois Niortais
        $userNIO = (new User())
            ->setEmail("secretary.chamoisniortais@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Yoann')
            ->setLastName("Singer")
            ->setIsVerified(1)
        ;
        $userNIO->setPassword(
            $this->encoder->encodePassword(
                $userNIO,
                "chamoisniortais"
            )
        );
        $manager->persist($userNIO);

        // Secrétaire Pau FC
        $userPau = (new User())
            ->setEmail("secretary.paufc@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Julien')
            ->setLastName("Castéra")
            ->setIsVerified(1)
        ;
        $userPau->setPassword(
            $this->encoder->encodePassword(
                $userPau,
                "paufc"
            )
        );
        $manager->persist($userPau);

        // Secrétaire Toulouse FC
        $userTFC = (new User())
            ->setEmail("secretary.toulouse@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Manon')
            ->setLastName("Fargues")
            ->setIsVerified(1)
        ;
        $userTFC->setPassword(
            $this->encoder->encodePassword(
                $userTFC,
                "toulouse"
            )
        );
        $manager->persist($userTFC);

        // Secrétaire Montpellier HSC
        $userMHSC = (new User())
            ->setEmail("secretary.montpellier@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Pierre')
            ->setLastName("Iaccarino")
            ->setIsVerified(1)
        ;
        $userMHSC->setPassword(
            $this->encoder->encodePassword(
                $userMHSC,
                "montpellier"
            )
        );
        $manager->persist($userMHSC);

        // Secrétaire Stade Rennais FC
        $userSRFC = (new User())
            ->setEmail("secretary.staderennais@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Thibaud')
            ->setLastName("Pommier")
            ->setIsVerified(1)
        ;
        $userSRFC->setPassword(
            $this->encoder->encodePassword(
                $userSRFC,
                "staderennais"
            )
        );
        $manager->persist($userSRFC);

        // Secrétaire FC Lorient
        $userFCL = (new User())
            ->setEmail("secretary.lorient@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Alexandre')
            ->setLastName("Dubois")
            ->setIsVerified(1)
        ;
        $userFCL->setPassword(
            $this->encoder->encodePassword(
                $userFCL,
                "lorient"
            )
        );
        $manager->persist($userFCL);

        // Secrétaire US Concarneau
        $userUSC = (new User())
            ->setEmail("secretary.concarneau@gmail.com")
            ->setTel("0557889996")
            ->setFirstName('Cyril')
            ->setLastName("Tournier")
            ->setIsVerified(1)
        ;
        $userUSC->setPassword(
            $this->encoder->encodePassword(
                $userUSC,
                "concarneau"
            )
        );
        $manager->persist($userUSC);

        $manager->flush();

        /**
         * Ajout des Stades
         */

        // Matmut Atlantique
        $picture = new File($this->fixturesDirectory. "/matmut_atlantique.jpg","matmut_atlantique");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        // dd($fileName);
        $stadiumFCGB = (new Stadium())
            ->setName("Stade Matmut Atlantique")
            ->setAddress("27 cours Jules Lamdoumègue, 33500 Bordeaux")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumFCGB);

        // Stade René Gaillard - Niort
        $picture = new File($this->fixturesDirectory. "/marcel_deflandre.jpg","marcel_deflandre");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumNIO = (new Stadium())
            ->setName("Stade René Gaillard")
            ->setAddress("23 rue Chef de Baie, 79000 Niort")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumNIO);

        // Stade du Hameau - Pau
        $picture = new File($this->fixturesDirectory. "/Hameau-stade.png","hameau_stade");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumPau = (new Stadium())
            ->setName("Stade du Hameau")
            ->setAddress("56 avenue du Rugby, 64000 Pau")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumPau);

        // Stadium - Toulouse
        $picture = new File($this->fixturesDirectory. "/stadium-toulouse.jpg","stadium_toulouse");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumTFC = (new Stadium())
            ->setName("Stadium de Toulouse")
            ->setAddress("258 avenue Airbus, 31420 Toulouse")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumTFC);

        // Stade de la Mosson - Montpellier
        $picture = new File($this->fixturesDirectory. "/stade_mosson.jpg","stade_mosson");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumMHSC = (new Stadium())
            ->setName("Stadium de la Mosson")
            ->setAddress("124 avenue de la Méditérrannée, 34920 Montpellier")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumMHSC);

        // Roazhon Park - Rennes
        $picture = new File($this->fixturesDirectory. "/roazhon_park.png","roazhon_park");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumSRFC = (new Stadium())
            ->setName("Roazhon Park")
            ->setAddress("57 route de Lorient, 35000 Rennes")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumSRFC);

        // Stade du Moustoir - Lorient
        $picture = new File($this->fixturesDirectory. "/stade_moustoir.jpg","stade_moustoir");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumFCL = (new Stadium())
            ->setName("Stade du Moustoir")
            ->setAddress("25 cours des Digues, 56000 Lorient")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumFCL);

        // Stade René Piriou - Concarneau
        $picture = new File($this->fixturesDirectory. "/stade_concarneau.jpg","stade_concarneau");
        $fileName = $this->fileUploader->uploadImage($picture, 'stadium');
        $stadiumUSC = (new Stadium())
            ->setName("Stadium René Piriou")
            ->setAddress("23 rue de la Ville Close, 29000 Concarneau")
            ->setPicture($fileName)
        ;

        $manager->persist($stadiumUSC);

        $manager->flush();

        /**
         * Ajout des Ligues
         */

        $leagueNA = (new League())
            ->setName("Ligue de Football de Nouvelle-Aquitaine")
            ->setWebsite("https://malfna.fr/")
            ->setAddress("102 rue d'Angoulème, 17560 Rochefort")
            ->setResponsible($userNA)
            ;

        $manager->persist($leagueNA);

        $leagueOcc = (new League())
            ->setName("Ligue de Football d'Occitanie")
            ->setWebsite("https://occitanie.fff.fr/")
            ->setAddress("238 Avenue des Cerisiers, 31560 Toulouse")
            ->setResponsible($userOcc)
            ;

        $manager->persist($leagueOcc);

        $leagueBre = (new League())
            ->setName("Ligue de Football de Bretagne")
            ->setWebsite("https://footbretagne.fff.fr/")
            ->setAddress("15 impasse des Vents, 29000 Quimper")
            ->setResponsible($userBre)
            ;
        
        $manager->persist($leagueBre);

        $leagueNor = (new League())
            ->setName("Ligue de Football de Normandie")
            ->setWebsite("https://normandie.fff.fr/")
            ->setAddress("56 rue Binaud, 14000 Caen")
            ->setResponsible($userNor)
            ;
        
        $manager->persist($leagueNor);

        $leagueCor = (new League())
            ->setName("Ligue de Football de Corse")
            ->setWebsite("https://corse.fff.fr/")
            ->setAddress("148 rue de la plage, 20584 Ajaccio")
            ->setResponsible($userCor)
            ;
        
        $manager->persist($leagueCor);

        $manager->flush();

        /**
         * Ajout des Clubs
         */

        // Girondins de Bordeaux
        $logo = new File($this->fixturesDirectory. "/Logo_FCGB.png","logo_fcgb");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubFCGB = (new Club())
            ->setName("FC Girondins de Bordeaux")
            ->setAcronym("FCGB")
            ->setWebsite("https://www.girondins.com/fr")
            ->setStadium($stadiumFCGB)
            ->setLeague($leagueNA)
            ->setLogo($fileName)
            ->setSecretary($userFCGB)
        ;
        
        $manager->persist($clubFCGB);

        // Chamois Niortais
        $logo = new File($this->fixturesDirectory. "/Logo_Chamois_Niortais.svg","logo_chamois_niortais");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubNIO = (new Club())
            ->setName("Chamois Niortais FC")
            ->setAcronym("NIO")
            ->setWebsite("https://www.chamoisniortais.fr/")
            ->setStadium($stadiumNIO)
            ->setLeague($leagueNA)
            ->setLogo($fileName)
            ->setSecretary($userNIO)
        ;

        $manager->persist($clubNIO);

        // Pau FC
        $logo = new File($this->fixturesDirectory. "/Logo_Pau_FC.svg","logo_pau_fc");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubPau = (new Club())
            ->setName("Pau FC")
            ->setAcronym("PAU")
            ->setWebsite("https://paufc.fr/")
            ->setStadium($stadiumPau)
            ->setLeague($leagueNA)
            ->setLogo($fileName)
            ->setSecretary($userPau)
        ;
        
        $manager->persist($clubPau);

        // Toulouse FC
        $logo = new File($this->fixturesDirectory. "/Logo_Toulouse_FC.svg","logo_tfc");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubTFC = (new Club())
            ->setName("Toulouse FC")
            ->setAcronym("TFC")
            ->setWebsite("https://toulousefc.com/fr")
            ->setStadium($stadiumTFC)
            ->setLeague($leagueOcc)
            ->setLogo($fileName)
            ->setSecretary($userTFC)
        ;
        
        $manager->persist($clubTFC);

        $manager->flush();

        // Montpellier HSC
        $logo = new File($this->fixturesDirectory. "/Logo_MHSC.png","logo_mhsc");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubMHSC = (new Club())
            ->setName("Montpellier Hérault SC")
            ->setAcronym("MHSC")
            ->setWebsite("https://www.mhscfoot.com/")
            ->setStadium($stadiumMHSC)
            ->setLeague($leagueOcc)
            ->setLogo($fileName)
            ->setSecretary($userMHSC)
        ;
        
        $manager->persist($clubMHSC);

        // Stade Rennais FC
        $logo = new File($this->fixturesDirectory. "/Logo_Stade_Rennais_FC.png","logo_tfc");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubSRFC = (new Club())
            ->setName("Stade Rennais FC")
            ->setAcronym("SRFC")
            ->setWebsite("https://www.staderennais.com/")
            ->setStadium($stadiumSRFC)
            ->setLeague($leagueBre)
            ->setLogo($fileName)
            ->setSecretary($userSRFC)
        ;
        
        $manager->persist($clubSRFC);

        // FC Lorient
        $logo = new File($this->fixturesDirectory. "/logo-fc-lorient.png","logo_fcLorient");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubFCL = (new Club())
            ->setName("FC Lorient")
            ->setAcronym("FCL")
            ->setWebsite("https://fclweb.fr/")
            ->setStadium($stadiumFCL)
            ->setLeague($leagueBre)
            ->setLogo($fileName)
            ->setSecretary($userFCL)
        ;
        
        $manager->persist($clubFCL);

        // US Concarneau
        $logo = new File($this->fixturesDirectory. "/Logo_US_Concarneau.png","logo_usconcarneau");
        $fileName = $this->fileUploader->uploadImage($logo, 'logo');
        $clubUSC = (new Club())
            ->setName("US Concarneau")
            ->setAcronym("USC")
            ->setWebsite("https://www.usc-concarneau.com/")
            ->setStadium($stadiumUSC)
            ->setLeague($leagueBre)
            ->setLogo($fileName)
            ->setSecretary($userUSC)
        ;
        
        $manager->persist($clubUSC);

        $manager->flush();

    }
}