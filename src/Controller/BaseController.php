<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextType, DateType};

use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Entreprise;
use App\Entity\OffreEmploi;
use App\Entity\Chomeur;
use App\Classe\ConnexionChomeur;



class BaseController extends AbstractController
{
    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/', name:'accueil')]
    public function accueil(ManagerRegistry $doctrine, Request $request) :Response
     {
        $request->getSession()->remove('chomeur_connecte');  
      
        $nbChomeurs = $doctrine->getRepository(Chomeur::class)->CountEntities();
        $nbOE       = $doctrine->getRepository(OffreEmploi::class)->CountEntities();
        $nbEntrep   = $doctrine->getRepository(Entreprise::class)->CountEntities();

        $connexion = new ConnexionChomeur;

        $formBuilder = $this->CreateFormBuilder($connexion)
           ->add('nom')
           ->add('Valider', SubmitType::class);

        $form = $formBuilder->getForm(); 
        
        $form->HandleRequest($request); 
        if ($form->isSubmitted())
        {
            if($form->isValid())
            {
                $chomeur = $doctrine->getManager()->getRepository(Chomeur::class)->findOneBy(['nom'=>$connexion->getNom()]);
                if (!empty($chomeur))
                {
                    $this->AddFlash("notice", "Bienvenue " . $chomeur->getNom());
                    $request->getSession()->set('chomeur_connecte', $chomeur->getNom() );
                    return $this->redirectToRoute('accueil_chomeur');
                }
            }
            $this->AddFlash("erreur", "Erreur de connexion");
        }
        return $this->render('accueil.html.twig', ['nbChomeurs' => $nbChomeurs,
                                                   'nbOE' => $nbOE,
                                                   'nbEntrep' => $nbEntrep,
                                                   'form' => $form->CreateView() ]);
    }

   
   
   
    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/accueil_chomeur', name:'accueil_chomeur')]
    public function accueil_chomeur(ManagerRegistry $doctrine, Request $request) :Response
     {
        $filtreEntrepNom = "";
        $tabEntrep = $this->TraiterEntrep($doctrine, $request, $tabOffresEmplois, $filtreEntrepNom  );

        return $this->render('accueil_chomeur.html.twig', ['tabOE' => $tabOffresEmplois, 
                                                   'tabEntrep' => $tabEntrep,
                                                   'filtreEntrepNom' => $filtreEntrepNom ]);
    }

    //-------------------------------------
    //
    //-------------------------------------
    #[Route('/ancien_accueil', name:'ancien_accueil')]
    public function ancien_accueil(ManagerRegistry $doctrine, Request $request) :Response
     {
      
        $tabChomeurs = $this->TraiterChomeurs( $doctrine, $request);
        $filtreEntrepNom = "";
        $tabEntrep = $this->TraiterEntrep($doctrine, $request, $tabOffresEmplois, $filtreEntrepNom  );

        return $this->render('ancien_accueil.html.twig', ['tabOE' => $tabOffresEmplois, 
                                                   'tabChomeurs' => $tabChomeurs,
                                                   'tabEntrep' => $tabEntrep,
                                                   'filtreTexte' => $request->getSession()->get('filtreTexte'),
                                                   'filtreEntrepNom' => $filtreEntrepNom ]);
    }

    //--------------------------------------
    //
    //--------------------------------------
    private function TraiterEntrep($doctrine, $request, &$tabOffresEmplois, &$filtreEntrepNom)
    {
        // Zone ENTREPRISE                           
        $tabOffresEmplois = $doctrine->
            getManager()->
            getRepository(OffreEmploi::class)->
            findAll();

       $tabEntrep = $doctrine->
            getManager()->
            getRepository(Entreprise::class)->
            findAll();

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
        if (isset($entrepFiltre))
        {
            $filtreEntrepNom = $entrepFiltre->getNom();
        }
        return $tabEntrep;
    }
    
    //--------------------------------------
    //
    //--------------------------------------
    private function TraiterChomeurs($doctrine, $request)
    {
        $tabChomeurs = $doctrine->
            getManager()->
            getRepository(Chomeur::class)->
            findAll();

           // Zonwe TEXTE
        // Récup à partir du $_GET                            
        $texteRecherche = $request->query->get('texteRecherche');
        if (isset($texteRecherche)) 
        {
           if (empty($texteRecherche))
           { 
             $request->getSession()->remove("filtreTexte");
           }
           else
           {
              $request->getSession()->set("filtreTexte", $texteRecherche);
           }
        }

        $texteRecherche = $request->getSession()->get("filtreTexte");
        if (strlen($texteRecherche) > 0)
        {
            $tabChomeurs = $this->AppliqueCritere($tabChomeurs, $texteRecherche);
            if (count($tabChomeurs) == 0)
            {
                $this->addFlash("notice", "Aucun chômeur n'a un nom contenant '$texteRecherche'");
            }
        }
        return $tabChomeurs;
    }


    //--------------------------------------
    //
    //--------------------------------------
    private function AppliqueCritere($tabChomeurs, $crit)
    {
        $tabTmp = [];
        $crit = strtolower($crit);
        foreach($tabChomeurs as $unChomeur)
        {
            if ( strpos( strtolower($unChomeur->getNom()), $crit) !== false)
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
