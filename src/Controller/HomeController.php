<?php

namespace App\Controller;

use App\Repository\CommandeRepository;
use App\Repository\MaterielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(MaterielRepository $materielRepository, CommandeRepository $commandeRepository): Response
    {
        $materiels = $materielRepository->findBy([], ['nom' => 'ASC'], 5);
        $commandes = $commandeRepository->findAllOrderedByDate();
        $commandesRecentes = array_slice($commandes, 0, 5);
        
        return $this->render('home/index.html.twig', [
            'materiels' => $materiels,
            'commandes_recentes' => $commandesRecentes,
            'total_commandes' => count($commandes),
            'total_materiels' => count($materielRepository->findAll()),
        ]);
    }
}
