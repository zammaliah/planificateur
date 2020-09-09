<?php

namespace App\Controller;

use App\Repository\AdresseRepository;
use App\Repository\MotoRepository;
use App\Repository\LivreurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $livreurRepository;
    private $motoRepository;
    private $adresseRepository;

    function __construct(
        LivreurRepository $livreurRepository,
        MotoRepository $motoRepository,
        AdresseRepository $adresseRepository
        )
    {
        $this->livreurRepository = $livreurRepository;
        $this->motoRepository = $motoRepository;
        $this->adresseRepository = $adresseRepository;
    
        
    }
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $livreurs = $this->livreurRepository->findAll();
        $motos = $this->motoRepository->findAll();
        $adresses = $this->adresseRepository->findAll();
        return $this->render('home/index.html.twig', [
            'motos' => $motos,
            'livreurs' => $livreurs,
            'adresses' => $adresses,
        ]);
    }
}
