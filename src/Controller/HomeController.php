<?php

namespace App\Controller;

use App\Entity\Planification;
use App\Repository\AdresseRepository;
use App\Repository\MotoRepository;
use App\Repository\LivreurRepository;
use App\Repository\PlanificationRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $livreurRepository;
    private $motoRepository;
    private $adresseRepository;
    private $planificationRepository;
    private $em;

    function __construct(
        EntityManagerInterface $em,
        LivreurRepository $livreurRepository,
        MotoRepository $motoRepository,
        AdresseRepository $adresseRepository,
        PlanificationRepository $planificationRepository
        )
    {
        $this->livreurRepository = $livreurRepository;
        $this->motoRepository = $motoRepository;
        $this->adresseRepository = $adresseRepository;
        $this->planificationRepository = $planificationRepository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $nbrLivraisonToday = $this->planificationRepository->findNbrToDay();
        $nbrLivraisonSupToday = $this->planificationRepository->findNbrSupToDay();
        $lastTens = $this->planificationRepository->findLastTen();
        $livreurs = $this->livreurRepository->findAll();
        $motos = $this->motoRepository->findAll();
        $adresses = $this->adresseRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'motos' => $motos,
            'livreurs' => $livreurs,
            'adresses' => $adresses,
            'nbrLivraisonToday'=> $nbrLivraisonToday,
            'nbrLivraisonSupToday'=> $nbrLivraisonSupToday,
            'lastTens' => $lastTens
        ]);
    }

    /**
     * @Route("/events", name="events")
     */
    public function events()
    {
        $planifications = $this->planificationRepository->findAll();
        $rendu = [];
        foreach ($planifications as $value)
        {
            $rendu[] = [
                'id' =>'pl_livreur_'.$value->getId(),
                'title' => $value->getLivreur()->getName(),
                'start' => $value->getDate()->format('Y-m-d') 
            ];
            $rendu[] =  [
                'id' =>'pl_adresse_'.$value->getId(),
                'title' => $value->getAdresse()->getName(),
                'start' => $value->getDate()->format('Y-m-d')  
            ];
            $rendu[] =  [
                'id' =>'pl_moto_'.$value->getId(),
                'title' => $value->getMoto()->getName(),
                'start' => $value->getDate()->format('Y-m-d') 
            ];
            
        }
        $response=new Response();
        $response->setContent(json_encode($rendu));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }



    /**
     * @Route("/validate", name="validate")
     */
    public function validate(Request $request)
    {
        $moto_add = array_values($request->request->get('moto_add',[]));
        $adresse_add = array_values($request->request->get('adresse_add',[]));
        $livreur_add = array_values($request->request->get('livreur_add',[]));
        $list_delete = array_values($request->request->get('delete',[]));
        
        //add new events
        foreach($moto_add as $m){
            $date = $m['date'];
            $id_livreur = array_search($date, array_column($livreur_add, 'date'));
            $id_adresse = array_search($date, array_column($adresse_add, 'date'));
            if ($id_livreur !== false && $id_adresse !== false) {
                $moto = $this->motoRepository->findOneBy(['name' => $m['titre']]);
                $adresse = $this->adresseRepository->findOneBy(['name' => $adresse_add[$id_adresse]['titre']]);
                $livreur = $this->livreurRepository->findOneBy(['name' => $livreur_add[$id_livreur]['titre']]);
                $dateTime = new DateTime($date);
                $planification = new Planification();
                $planification->setAdresse($adresse);
                $planification->setLivreur($livreur);
                $planification->setMoto($moto);
                $planification->setDate($dateTime);
                $this->em->persist($planification);
                $this->em->flush();
                unset($adresse_add[$id_adresse]);
                unset($livreur_add[$id_livreur]);
                $adresse_add = array_values($adresse_add);
                $livreur_add = array_values($livreur_add);
            }
            
        }

        //delete events 
        foreach ($list_delete as $value) {
            $id_delete = intval(explode('_', $value['id'])[2]);
            $planification = $this->planificationRepository->find($id_delete);
            if ($planification){
                $this->em->remove($planification);
                $this->em->flush();
            }
        }


        $response=new Response();
        $response->setContent(json_encode(['success']));
        $response->headers->set('Content-Type', 'application/json');
        $this->addFlash('success', 'Votre Calendrier à été bien à jour' );
        return $response;
    }

  

}
