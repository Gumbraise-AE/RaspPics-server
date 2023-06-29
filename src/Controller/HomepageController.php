<?php

namespace App\Controller;

use App\Repository\RaspAuthorizationRepository;
use App\Repository\RaspProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        RaspProjectRepository       $raspProjectRepository,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $authorizedRasps = $raspProjectRepository->findByAuthorizatedUser($this->getUser());
        $ownRasps = $raspProjectRepository->findBy(['author' => $this->getUser()]);

        return $this->render('homepage/index.html.twig', [
            'authorizedRasps' => $authorizedRasps,
            'ownRasps' => $ownRasps,
        ]);
    }
}
