<?php

namespace App\Controller;

use App\Entity\RaspProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rasp')]
class RaspController extends AbstractController
{
    #[Route('/{id}', name: 'app_single_rasp')]
    public function single(RaspProject $raspProject): Response
    {
        return $this->render('rasp/single.html.twig', [
            'controller_name' => 'RaspController',
            'rasp' => $raspProject,
            'user_ip' => $_SERVER['REMOTE_ADDR']
        ]);
    }
}
