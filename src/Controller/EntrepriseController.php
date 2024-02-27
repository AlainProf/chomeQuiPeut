<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Form\Extension\Core\Type\{TextType, SubmitType};

use App\Entity\OffreEmploi;
use App\Entity\Entreprise;
use Doctrine\Persistence\ManagerRegistry;

class EntrepriseController extends AbstractController
{
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

       // On crée le formBuilder, remarquez qu'on passe l'entité à hydrater
       $formBuilder = $this->createFormBuilder($entreprise)
                       ->add('nom', TextType::class)
                       ->add('contact', TextType::class)
                       ->add('Ajouter', SubmitType::class);

       $formulaire = $formBuilder->getForm();

       $formulaire->handleRequest($request);

       if ($formulaire->isSubmitted())
       {
           // Nous somme en mode soumission de form
           {
               //Est-ce que les donnée de l'utilsateurs sont valides
               if ($formulaire->isValid())
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
                                        array('formulaire' => $formulaire->CreateView()));
       

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
