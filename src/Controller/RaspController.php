<?php

namespace App\Controller;

use App\Entity\RaspProject;
use App\Repository\RaspPicRepository;
use Imagick;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

#[Route('/rasp')]
class RaspController extends AbstractController
{
    #[Route('/{id}', name: 'app_single_rasp')]
    public function single(RaspProject $raspProject): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('rasp/single.html.twig', [
            'controller_name' => 'RaspController',
            'rasp' => $raspProject,
            'user_ip' => $_SERVER['REMOTE_ADDR']
        ]);
    }


    #[Route('/{id}/gif/{freq}', name: 'app_single_rasp_gif', defaults: ['freq' => 'day'])]
    public function singleGif(
        RaspProject       $raspProject,
        RaspPicRepository $raspPicRepository,
        UploaderHelper    $uploaderHelper,
                          $freq,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $limit = 48;
        $day = 1;

        switch ($freq) {
            default:
            case 'day':
                break;
            case 'week':
                $day = 7;
                break;
            case 'month':
                $day = 31;
                break;
        }

        $limit = $limit * $day;

        $raspPicsAll = array_reverse($raspPicRepository->findBy(['raspProject' => $raspProject], ["createdAt" => "DESC"], $limit));

        $raspPics = array();
        foreach ($raspPicsAll as $key => $entity) {
            if ($key % $day == 0) { // Si la clé est paire
                $raspPics[] = $entity; // Ajouter l'entité au tableau filtré
            }
        }

        // Créer un objet Imagick pour créer le GIF
        $imagick = new Imagick();
        $imagick->setFormat('gif');

        $publicPath = $this->getParameter('kernel.project_dir') . '/public';

        // Ajouter chaque image à l'objet Imagick
        foreach ($raspPics as $raspPic) {
            $frame = new Imagick();
            $imagePath = $publicPath . $uploaderHelper->asset($raspPic, 'picFile');
            $frame->readImage($imagePath);
            $frame->resizeImage(800, 600, Imagick::FILTER_LANCZOS, 1);
            $frame->setImageFormat('webp');
            $frame->setImageCompressionQuality(80);
            $frame->setImageDelay(10);
            $imagick->addImage($frame);
        }

        // Écrire le GIF dans une variable
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(0);
        $gifData = $imagick->getImagesBlob();

        // Renvoyer le GIF en tant que réponse HTTP
        return new Response($gifData, 200, [
            'Content-Type' => 'image/gif',
            'Content-Disposition' => 'attachment; filename="raspPics.gif"',
        ]);
    }
}
