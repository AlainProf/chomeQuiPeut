<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\OffreEmploi;
use App\Entity\Chomeur;

class BaseController extends AbstractController
{
    #[Route('/', name:'accueil')]
    public function accueil(ManagerRegistry $doctrine)
    {
        $tabOffresEmplois = $doctrine->
                             getManager()->
                             getRepository(OffreEmploi::class)->
                             findAll();

        $tabChomeurs = $doctrine->
                             getManager()->
                             getRepository(Chomeur::class)->
                             findAll();



        return $this->render('accueil.html.twig', ['tabOE' => $tabOffresEmplois, 'tabChomeurs' => $tabChomeurs]);
    }
}
