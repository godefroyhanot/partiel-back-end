<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeMateriel;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAllOrderedByDate(),
        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();
        
        // Ajouter une ligne vide par défaut
        $commandeMateriel = new CommandeMateriel();
        $commande->addCommandeMateriel($commandeMateriel);
        
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Vérifier la disponibilité et mettre à jour les stocks
                foreach ($commande->getCommandeMateriels() as $commandeMateriel) {
                    $materiel = $commandeMateriel->getMateriel();
                    $quantiteDemandee = $commandeMateriel->getQuantite();
                    
                    if ($materiel->getQuantiteDisponible() < $quantiteDemandee) {
                        $this->addFlash('error', sprintf(
                            'Stock insuffisant pour %s. Disponible: %d, Demandé: %d',
                            $materiel->getNom(),
                            $materiel->getQuantiteDisponible(),
                            $quantiteDemandee
                        ));
                        
                        return $this->render('commande/new.html.twig', [
                            'commande' => $commande,
                            'form' => $form,
                        ]);
                    }
                    
                    // Définir le prix unitaire au moment de la commande
                    $commandeMateriel->setPrixUnitaire($materiel->getPrixLocation());
                    
                    // Réduire le stock disponible
                    $materiel->setQuantiteDisponible($materiel->getQuantiteDisponible() - $quantiteDemandee);
                }
                
                // Calculer le prix total
                $commande->calculerPrixTotal();
                
                $entityManager->persist($commande);
                $entityManager->flush();

                $this->addFlash('success', 'La commande a été créée avec succès.');

                return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
                
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la commande.');
            }
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/rendre', name: 'app_commande_rendre', methods: ['POST'])]
    public function rendre(Commande $commande, EntityManagerInterface $entityManager): Response
    {
        try {
            // Remettre en stock tous les matériels de la commande
            foreach ($commande->getCommandeMateriels() as $commandeMateriel) {
                $materiel = $commandeMateriel->getMateriel();
                $quantiteARestituer = $commandeMateriel->getQuantite();
                
                $materiel->setQuantiteDisponible($materiel->getQuantiteDisponible() + $quantiteARestituer);
            }
            
            // Supprimer la commande
            $entityManager->remove($commande);
            $entityManager->flush();

            $this->addFlash('success', 'La commande a été rendue et supprimée avec succès.');
            
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la restitution de la commande.');
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }
}
