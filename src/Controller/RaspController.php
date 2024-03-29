<?php

namespace App\Controller;

use App\Entity\RaspPic;
use App\Entity\RaspProject;
use App\Repository\RaspPicRepository;
use Imagick;
use ImagickException;
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
    public function single(
        RaspProject       $raspProject,
        RaspPicRepository $raspPicRepository,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$raspProject->getAuthorizedUsers()->contains($this->getUser()) && $this->getUser() !== $raspProject->getAuthor()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('rasp/single.html.twig', [
            'rasp' => $raspProject,
            'raspPics' => $raspPicRepository->findBy(['raspProject' => $raspProject], ['createdAt' => 'DESC'], 48),
            'user_ip' => $_SERVER['REMOTE_ADDR']
        ]);
    }

    #[Route('/pics/{id}', name: 'app_delete_rasp_pic', methods: ['DELETE'])]
    public function deleteRaspPic(
        RaspPic           $raspPic,
        RaspPicRepository $raspPicRepository,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $raspPic->getRaspProject()->getAuthor()) {
            return $this->redirectToRoute('app_login');
        }

        $raspPicRepository->remove($raspPic, true);

        return new Response();
    }

    /**
     * @throws ImagickException
     */
    #[Route('/{id}/gif/{freq}', name: 'app_single_rasp_gif', defaults: ['freq' => 'day'])]
    public function singleGif(
        RaspProject       $raspProject,
        RaspPicRepository $raspPicRepository,
        UploaderHelper    $uploaderHelper,
                          $freq,
    ): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$raspProject->getAuthorizedUsers()->contains($this->getUser()) && $this->getUser() !== $raspProject->getAuthor()) {
            return $this->redirectToRoute('app_login');
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

        if ($freq == 'month-special') {
            $limit = 900;
        }

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
            $frame->quantizeImage(64, Imagick::COLORSPACE_RGB, 0, false, false);
            $frame->optimizeImageLayers();
            $frame->setImageCompression(Imagick::COMPRESSION_JPEG);
            $frame->setImageCompressionQuality(80);
            $frame->setImageDelay(10);
            $imagick->addImage($frame);
        }

        // Réduire le nombre de couleurs
        $imagick->quantizeImage(64, Imagick::COLORSPACE_RGB, 0, false, false);

        // Optimiser les frames
        $imagick->optimizeImageLayers();

        // Écrire le GIF dans une variable
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(80);
        $gifData = $imagick->getImagesBlob();

        // Renvoyer le GIF en tant que réponse HTTP
        return new Response($gifData, 200, [
            'Content-Type' => 'image/gif',
            'Content-Disposition' => 'filename="raspPics-' . $freq . '-' . date('c') . '.gif"',
        ]);
    }
}
