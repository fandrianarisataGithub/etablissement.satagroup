<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Membre;
use App\Entity\Article;
use App\Entity\FileUpload;
use App\Entity\Etablissement;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EtablissementRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ArticleController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/profile/articles/{etablissement_id}", name="articles")
     */
    public function articles(
        ArticleRepository $repoArticle,
        Request $request,
        PaginatorInterface $paginator,
        $etablissement_id,
        EtablissementRepository $repoEtablissement
    ): Response {
        $etablissement = $request->getSession()->get('session')['etablissement'];

        $query = $repoArticle->queryFindAllByEtab($etablissement);
        //dd($query);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );

        //dd($articles);
        return $this->render("front/articles.html.twig", [
            "pagination" => $pagination,
        ]);
    }

    /**
     * @Route("/profile/article/{id}", name="article")
     */
    public function article(Article $article): Response
    {

        return $this->render("front/show_article.html.twig", [
            "article" => $article,
        ]);
    }


    /**
     * @Route("/profile/add_article", name="add_article")
     * @Route("/profile/ajax/edit_article/{id}", name="edit_article_ajax")
     */
    public function add_article(Request $request, ?Article $article): Response
    {
        $response = new Response();
        $etablissement_id = $request->getSession()->get('session')['etablissement']->getId();
        if ($request->isXmlHttpRequest()) {
            if(!$article){
                $article = new Article();
                $article->setCreatedAt(new \DateTime());
            }
            $files_in_request = $request->files;
            $images = $files_in_request->get('file_images');
            //dd($images);
            $fichiers = $files_in_request->get('file_files');
            $video = $files_in_request->get('file_video');
            $user_id = $request->get('user_id');
            $titre = $request->get('titre');
            $contenu = $request->get('contenu');
            $article->setTitre($titre);
            $article->setContenu($contenu);
            $data = "ok";
            $tab_ext_image = ["jpg", "JPG", "jpeg", "png", "PNG"];
            $tab_ext_fichier = ["txt", "pdf", "doc", "docm", "docx", "html", "xml"];
            $tab_ext_video = ["avi", "mov", "mkv", "mp4", "3gp", "AVI", "MOV", "MP4"];

            // add user and createdAt

            if ($user_id != "") {
                if ($images) {
                    
                    foreach ($images as $image) {
                        if (in_array($image->guessExtension(), $tab_ext_image)) {
                            
                            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                            
                            $newFilename = uniqid() . '.' . $image->guessExtension();
                            
                            try {
                                $ext = $image->guessExtension();
                                $image->move(
                                    $this->getParameter('images_article'),
                                    $newFilename
                                );
                                
                                $file = new FileUpload();
                                $file->setFiletype("image");
                                $file->setExt($ext);
                                $file->setUrl($newFilename);
                                $file->setArticle($article);
                                
                                $this->em->persist($file);
                            } catch (FileException $e) {
                                $data = "error";
                            }
                        }
                    }
                }

                if ($fichiers) {
                    foreach ($fichiers as $fichier) {

                        if (in_array($fichier->guessExtension(), $tab_ext_fichier)) {
                            $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);

                            $newFilename = uniqid() . '.' . $fichier->guessExtension();

                            try {
                                $ext = $fichier->guessExtension();
                                $fichier->move(
                                    $this->getParameter('fichiers_article'),
                                    $newFilename
                                );
                                $file = new FileUpload();
                                $file->setFiletype("fichier");
                                $file->setExt($ext);
                                $file->setUrl($newFilename);
                                $file->setArticle($article);
                                $this->em->persist($file);
                            } catch (FileException $e) {
                                $data = "error";
                            }
                        }
                    }
                }

                if ($video) {
                    // dd('tonga');
                    if (in_array($video->guessExtension(), $tab_ext_video)) {
                        $originalFilename = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);

                        $newFilename = uniqid() . '.' . $video->guessExtension();

                        try {
                            $ext = $video->guessExtension();
                            $video->move(
                                $this->getParameter('videos_article'),
                                $newFilename
                            );
                            $file = new FileUpload();
                            $file->setFiletype("video");
                            $file->setUrl($newFilename);
                            $file->setExt($ext);
                            $file->setArticle($article);
                            $this->em->persist($file);
                        } catch (FileException $e) {
                            $data = "error";
                        }
                    }
                }

                $article->setMembre($this->getDoctrine()->getRepository(Membre::class)->find($user_id));
                $article->setEtablissement($this->getDoctrine()->getRepository(Etablissement::class)->find($etablissement_id));
                //dd($etablissement_id);
                
                $this->em->persist($article);
                $this->em->flush();
            }

            $data = json_encode($data);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
        }

        return $response;
    }

    /**
     * @Route("/profile/delete_article/{id}", name="delete_article")
     */
    public function delete_article(KernelInterface $ker, Article $article, Request $request): Response
    {
        $projectRoot = $ker->getProjectDir();

        $path_image = $projectRoot . '/public/uploads/article/images';
        $path_fichier = $projectRoot . '/public/uploads/article/fichiers';
        $path_video = $projectRoot . '/public/uploads/article/videos';

        $fileSystem = new Filesystem();
        // $fileSystem->remove($path . '609a6197f3cc3.jpeg');
        $fichiers = $this->getDoctrine()->getRepository(FileUpload::class)->findBy(['article' => $article]);
        foreach ($fichiers as $fichier) {
            $url = $fichier->getUrl();
            $type = $fichier->getTypeFile();
            if ($type == "image") {
                $fileSystem->remove($path_image . "/" . $url);
            }
            if ($type == "fichier") {
                $fileSystem->remove($path_fichier . "/" . $url);
            }
            if ($type == "video") {
                $fileSystem->remove($path_video . "/" . $url);
            }
            $this->em->remove($fichier);
        }
        $this->em->remove($article);
        $this->em->flush();

        $etablissement_id = $request->getSession()->get('session')['etablissement']->getId();

        return $this->redirectToRoute("articles", ["etablissement_id" => $etablissement_id]);
    }

    /**
     * @Route("/profile/edit_article/{id}", name="edit_article")
     */
    public function edit_article(Article $article) : Response
    {

        return $this->render("front/edit_article.html.twig", [
            "article" => $article,
        ]);
    }
}
