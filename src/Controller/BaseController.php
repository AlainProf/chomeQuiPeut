<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Entreprise;
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

        $tabEntrep = $doctrine->
                             getManager()->
                             getRepository(Entreprise::class)->
                             findAll();
        


        return $this->render('accueil.html.twig', ['tabOE' => $tabOffresEmplois, 
                                                   'tabChomeurs' => $tabChomeurs,
                                                   'tabEntrep' => $tabEntrep ]);
    }

    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/filtre', name:'accueilFiltre')]
    public function accueilFiltre(ManagerRegistry $doctrine, Request $request)
    {

        $tabChomeurs = $doctrine->
                             getManager()->
                             getRepository(Chomeur::class)->
                             findAll();

        // Récup à partir du $_GET                            
        $texteRecherche = $request->query->get('texteRecherche');
        //dd($texteRecherche);

        if (strlen($texteRecherche) > 0 )
        {
            $tabChomeurs = $this->AppliqueCritere($tabChomeurs, $texteRecherche);
            if (count($tabChomeurs) == 0)
            {
                $this->addFlash("notice", "Aucune chômeur n'a un nom contenant '$texteRecherche'");
            }
        }
        else
        {
            $this->addFlash("notice", "Veuillez remplir le champ de recherche");
        }


        $tabOffresEmplois = $doctrine->
                             getManager()->
                             getRepository(OffreEmploi::class)->
                            findAll();
                            
        // Récup à partir du $_POST                            
        $IdEntrepFiltree = $request->request->get('filtreEntrep');
        
        if (isset($IdEntrepFiltree))
        {
           if ($IdEntrepFiltree != "0")
           {
            $entrepFiltree = $doctrine->
                      getManager()->
                      getRepository(Entreprise::class)->
                      find($IdEntrepFiltree);

            $tabOffresEmplois = $entrepFiltree->getOffresEmplois();
           } 
        }
        //dd("id de l'entrepr: $IdEntrepFiltree");

        $tabEntrep = $doctrine->
                      getManager()->
                      getRepository(Entreprise::class)->
                      findAll();

        return $this->render('accueil.html.twig', ['tabOE' => $tabOffresEmplois, 
                                                   'tabChomeurs' => $tabChomeurs,
                                                   'tabEntrep' => $tabEntrep]);
    }

    //--------------------------------------
    //
    //--------------------------------------
    private function AppliqueCritere($tabChomeurs, $crit)
    {
        $tabTmp = [];
        foreach($tabChomeurs as $unChomeur)
        {
            if ( strpos( $unChomeur->getNom(), $crit) !== false)
            {
                $tabTmp[] = $unChomeur;
            }
        }
        return $tabTmp;
    }
}
