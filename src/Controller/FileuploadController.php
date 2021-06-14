<?php

namespace App\Controller;

use App\Entity\FileUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileuploadController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/profile/delete_file/{id}", name="delete_file")
     */
    public function index(Request $request, FileUpload $fileUpload, KernelInterface $ker): Response
    {
        $response = new Response();
        if($request->isXmlHttpRequest()){
            // look out of the type of file
            $extension = $fileUpload->getExt();

            $tab_ext_image = ["jpg", "JPG", "jpeg", "png", "PNG"];
            $tab_ext_fichier = ["txt", "pdf", "doc", "docm", "docx", "html", "xml"];
            $tab_ext_video = ["avi", "mov", "mkv", "mp4", "3gp", "AVI", "MOV", "MP4"];

            $projectRoot = $ker->getProjectDir();

            $path_image = $projectRoot . '/public/uploads/article/images';
            $path_fichier = $projectRoot . '/public/uploads/article/fichiers';
            $path_video = $projectRoot . '/public/uploads/article/videos';

            $fileSystem = new Filesystem();

            if(in_array($extension, $tab_ext_image)){
                $fileSystem->remove($path_image . "/" . $fileUpload->getUrl());
            }
            if (in_array($extension, $tab_ext_fichier)) {
                $fileSystem->remove($path_fichier . "/" . $fileUpload->getUrl());
            }
            if (in_array($extension, $tab_ext_video)) {
                $fileSystem->remove($path_video . "/" . $fileUpload->getUrl());
            }

            $this->em->remove($fileUpload);
            $this->em->flush();

            $data = json_encode("ok");
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);

        }
        return $response;
    }

}
