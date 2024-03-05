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
    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/', name:'accueil')]
    public function accueil(ManagerRegistry $doctrine, Request $request)
    {

        $tabChomeurs = $doctrine->
                             getManager()->
                             getRepository(Chomeur::class)->
                             findAll();

        $tabOffresEmplois = $doctrine->
                             getManager()->
                             getRepository(OffreEmploi::class)->
                            findAll();

        $tabEntrep = $doctrine->
                            getManager()->
                            getRepository(Entreprise::class)->
                            findAll();
      

        // Récup à partir du $_GET                            
        $texteRecherche = $request->query->get('texteRecherche');
        if (isset($texteRecherche)) 
        {
            if (strlen($texteRecherche) > 0)
            {
                $request->getSession()->set("filtreTexte", $texteRecherche);
            }
            else
            {
                $texteRecherche = $request->getSession()->get("filtreTexte");
            }
            if (empty($texteRecherche))
            {
                $request->getSession()->remove("filtreTexte");
            }
        }

        // TODO:   Si je souments un formulaire vide ne remove le filtre texte

        $texteRecherche = $request->getSession()->get("filtreTexte");

        $tabChomeurs = $this->AppliqueCritere($tabChomeurs, $texteRecherche);
        if (count($tabChomeurs) == 0)
        {
            $this->addFlash("notice", "Aucun chômeur n'a un nom contenant '$texteRecherche'");
        }

                            
        // Récup à partir du $_POST                            
        $IdEntrepFiltree = $request->request->get('filtreEntrep');
        if (isset($IdEntrepFiltree))
        {
           if ($IdEntrepFiltree != "0")
           {
               $request->getSession()->set("filtreEntrep", $IdEntrepFiltree);
           } 
           else
           {
              $request->getSession()->remove("filtreEntrep");
           }
        }

        $IdEntrepFiltree = $request->getSession()->get('filtreEntrep');
        if (isset($IdEntrepFiltree))
        {
            $entrepFiltre = $doctrine->
                    getManager()->
                    getRepository(Entreprise::class)->
                    find($IdEntrepFiltree);

            $tabOffresEmplois = $entrepFiltre->getOffresEmplois(); 
            if (count($tabOffresEmplois) == 0)                  
            {
                $this->addFlash("notice", "Aucune offre d'emploi pour l'entrepise '" . $entrepFiltre->getNom() . "'");
            }
        }

        // TODO:   Si je choisis "Toutes" $entrepFiltre est null et ça plante
        //$nomEntrepFiltree = $entrepFiltre->getNom();

        return $this->render('accueil.html.twig', ['tabOE' => $tabOffresEmplois, 
                                                   'tabChomeurs' => $tabChomeurs,
                                                   'tabEntrep' => $tabEntrep,
                                                   'filtreTexte' => $request->getSession()->get('filtreTexte'),
                                                   'filtreEntrep' => $request->getSession()->get('filtreEntrep')]);
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

    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/sessionVidage')]
    public function sessionVidange( Request $request)
    {
        $request->getSession()->clear();
        return $this->redirectToRoute('accueil');
    }
    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/sessionVoir')]
    public function sessionVoir(Request $request)
    {
//        $msg = "Texte: " . $request->getSession()->get('filtreTexte')
        dd($request->getSession()->all());
    }

    
}
