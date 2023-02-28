<?php

namespace App\Controller;

use App\Entity\CronTest;
use App\Form\CronTestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CronTestController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/cron/test', name: 'app_cron_test', methods: ['POST', 'GET'])]
    public function index(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $cronEntity = new CronTest();
        $form = $this->createForm(CronTestType::class, $cronEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cronEntity->setText($form->get('text')->getData());
            $entityManager->persist($cronEntity);
            $entityManager->flush();

            return new Response(null, Response::HTTP_OK);
        }

        throw $this->createNotFoundException('UHH');
    }
}
