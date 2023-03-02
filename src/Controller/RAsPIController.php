<?php

namespace App\Controller;

use App\Entity\RaspPic;
use App\Form\RaspPicType;
use App\Repository\RaspAuthorizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class RAsPIController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        RaspAuthorizationRepository $authorizationRepository,
    ): Response
    {
        return $this->render('api/index.html.twig', [
            'apis' => $authorizationRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RaspAuthorizationRepository $authorizationRepository
     * @return Response
     */
    #[Route('/rasp-pic', name: 'app_rasppic', methods: ['POST'])]
    public function rasppic(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        RaspAuthorizationRepository $authorizationRepository,
    ): Response
    {
        $raspPic = new RaspPic();
        $form = $this->createForm(RaspPicType::class, $raspPic);
        $form->handleRequest($request);

        if (!$request->headers->has('Authorization')) {
            return new Response(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
        }

        $authorization = $authorizationRepository->findOneBy(['id' => $request->headers->get('Authorization')]);
        if (!$authorization) {
            return new Response(Response::$statusTexts[Response::HTTP_UNAUTHORIZED], Response::HTTP_UNAUTHORIZED);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $raspPic->setRaspProject($authorization->getRaspProject());
            $entityManager->persist($raspPic);
            $entityManager->flush();

            return new Response(Response::$statusTexts[Response::HTTP_OK], Response::HTTP_OK);
        }

        throw $this->createNotFoundException();

        return $this->render('api/view.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
