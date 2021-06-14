<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class SummernoteController extends AbstractController
{
     /**
     * @Route("/profile/summernote/add_image", name="summernote_add_image")
     */
    public function summernote_add_image(Request $request): Response
    {
        $response = new Response();
        $data = "";
        if($request->isXmlHttpRequest()){
            $image = $request->files;
           $image = $image->get('file');
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
               
                $newFilename = uniqid() . '.' . $image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('image_by_summernote'),
                        $newFilename
                    );
                    $data = $newFilename;
                } catch (FileException $e) {
                    $data = "error";
                }
               
            }
            else {
                $data = "error";
            }
            
        }
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent("ok");
        return $response;
    }
}
