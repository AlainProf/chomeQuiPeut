<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\OffreEmploi;
use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Form\OffreEmploiType;


use Doctrine\Persistence\ManagerRegistry;

class EntrepriseController extends AbstractController
{
    
    #[Route('/chomeursPostulants')]
    public function chomeursPostulants(ManagerRegistry $doctrine): Response
    {
        $tabEntreprises = $doctrine->getManager()->getRepository(Entreprise::class)->findAll();

        return $this->render("chomeursPostulants.html.twig", ["entreprises" => $tabEntreprises]);
    }


    #[Route('/afficherEntreprise')]
    public function afficherEntreprise(ManagerRegistry $doctrine): Response
    {
        $tabEntreprises = $doctrine->
        getManager()->
        getRepository(Entreprise::class)->
        findAll();

        return $this->render('entreprises.html.twig', ['tabEntrep' => $tabEntreprises]);
    }


    #[Route('/creationEntreprise')]
    public function creationEntreprise(ManagerRegistry $doctrine, Request $request): Response
    {
       //Création de l'entité
       $entreprise = new Entreprise;

       $formExterne = $this->createForm(EntrepriseType::class, $entreprise);

       //$formulaire = $formBuilder->getForm();

       $formExterne->handleRequest($request);

       if ($formExterne->isSubmitted())
       {
           // Nous somme en mode soumission de form
           {
               //Est-ce que les donnée de l'utilsateurs sont valides
               if ($formExterne->isValid())
               {
                    $em = $doctrine->getManager();
                    $em->persist($entreprise);
                    $em->flush();

                    $this->addFlash('succes', "Entreprise " . $entreprise->getNom() . " créée avec succès");
                    return $this->redirectToRoute('accueil');
               }
               else
               {
                   // Les données contiennent des erreurs
               }
           }
       }
      
       return $this->render('ajouterEntreprise.html.twig',
                                        array('formulaire' => $formExterne->CreateView()));
       

    }
    
    
    #[Route('/creationOffreEmploi')]
    public function creationOffreEmploi(ManagerRegistry $doctrine, Request $request): Response
    {
        $oe = new OffreEmploi;
        $oe->setDatePublication(new \DateTime);
        $formExterne = $this->createForm(OffreEmploiType::class, $oe);

        $formExterne->handleRequest($request);
        
        if ($formExterne->isSubmitted())
        {
            // Nous somme en mode soumission de form
            {
                //Est-ce que les donnée de l'utilsateurs sont valides
                if ($formExterne->isValid())
                {
                     $em = $doctrine->getManager();
                     $em->persist($oe);
                     $em->flush();
 
                     $this->addFlash('succes', "Offre d'emploi " . $oe->getTitre() . " créée avec succès");
                     return $this->redirectToRoute('accueil');
                }
                else
                {
                    // Les données contiennent des erreurs
                    $this->addFlash('erreur', "Au moins une erreur dans les données fournies");
                }
            }
        }
        return $this->render('ajouterOffreEmploi.html.twig',
        array('formulaire' => $formExterne->CreateView()));
    }
    
    #[Route('/creationOffreEmploiHC')]
    public function creationOffreEmploiHC(ManagerRegistry $doctrine): Response
    {
        $entrepA = new Entreprise;
        $entrepA->setNom("Cegep de Singe et Rhum");
        $entrepA->setContact("Oliveir Paul-Hus");

        $entrepB = new Entreprise;
        $entrepB->setNom("Tim Horton");
        $entrepB->setContact("Ringo");

        $offreEmploi = new OffreEmploi();
        $offreEmploi->setTitre("Programmeur junior");
        $offreEmploi->setDescription("Junior et stagiaire bienvenus");
        $offreEmploi->setSalaireAnnuel(50000);
        $offreEmploi->setDatePublication(new \DateTime);
        $offreEmploi->setEntreprise($entrepA);

        $offreEmploiA = new OffreEmploi();
        $offreEmploiA->setTitre("Analyste QA");
        $offreEmploiA->setDescription("5 ans d'expérience");
        $offreEmploiA->setSalaireAnnuel(110000);
        $offreEmploiA->setDatePublication(new \DateTime);
        $offreEmploiA->setEntreprise($entrepA);

        $offreEmploiB = new OffreEmploi();
        $offreEmploiB->setTitre("Agent de pastoral");
        $offreEmploiB->setDescription("Animé la vie spirituel de la génération perdue");
        $offreEmploiB->setSalaireAnnuel("25000");
        $offreEmploiB->setDatePublication(new \DateTime);
        $offreEmploiB->setEntreprise($entrepB);


        $em = $doctrine->getManager();

        $em->persist($offreEmploi);
        $em->persist($offreEmploiA);
        $em->persist($offreEmploiB);

        $em->flush();
        
        return $this->RedirectToRoute('accueil');
    }
}
