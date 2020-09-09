<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Moto;
use App\Entity\Livreur;
use App\Entity\Adresse;
use App\Entity\Planification;
use DateInterval;
use DateTime;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
                // create 10 Motos 10 Adresses 10 Livreurs
                for ($i = 1; $i < 10; $i++) {
                    $moto = new Moto();
                    $livreur = new Livreur();
                    $adresse = new Adresse();
                    $planification = new Planification();

                    $moto->setName('moto '.$i);
                    $livreur->setName('livreur '.$i);
                    $adresse->setName('adresse '.$i);
                    
                    $manager->persist($moto);
                    $manager->persist($livreur);
                    $manager->persist($adresse);

                    $date = new DateTime();
                    $date->add(new DateInterval('P'.($i-1).'D'));

                    $planification->setAdresse($adresse);
                    $planification->setLivreur($livreur);
                    $planification->setMoto($moto);
                    $planification->setDate($date);

                    $manager->persist($planification);

                }

        $manager->flush();
    }
}
