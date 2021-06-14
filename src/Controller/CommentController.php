<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Membre;
use App\Services\DateServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->em = $entityManagerInterface;
    }
    /**
     * @Route("/profile/add_comment", name="add_comment")
     */
    public function add_comment(Request $request): Response
    {
        $response = new Response();
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $repoMembre = $this->getDoctrine()->getRepository(Membre::class);
        if($request->isXmlHttpRequest()){
            $content = $request->get('content_comment');
            $user_id = $request->get('user_id');
            $article_id = $request->get('article_id');
            $cratedAt = new \DateTime();
            $membre = $repoMembre->find($user_id);
            $article = $repoArticle->find($article_id);
            $comment = new Commentaire();

            $comment->setContenu($content);
            $comment->setCreatedAt($cratedAt);
            $comment->setMembre($membre);
            $comment->setArticle($article);

            $this->em->persist($comment);
            $this->em->flush();

            $data = json_encode("ok");
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);


        }
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/profile/list_comment", name="list_comment") 
     */
    public function list_comment(Request $request, DateServices $dateServices): Response
    {
        $response = new Response();
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        $repoComment = $this->getDoctrine()->getRepository(Commentaire::class);
        if ($request->isXmlHttpRequest()) {
            
            $article_id = $request->get('article_id');
            $article = $repoArticle->find($article_id);
            $comments = $repoComment->findBy(['article' => $article], ['createdAt' => 'DESC']);
            $html = '';
            foreach ($comments as $comment) {
                $html .= '
                    <div class="card card-white post">
                            <div class="post-heading">
                                <div class="float-left image">
                                    <img src="http://bootdey.com/img/Content/user_1.jpg" class="img-circle avatar" alt="user profile image">
                                </div>

                                <div class="float-left meta">
                                    <div class="title h5">
                                        <a href="#">
                                            <b>'. $comment->getMembre()->getNom() .'</b>
                                        </a>
                                    </div>
                                    <h6 class="text-muted time">' . $dateServices->agoDate($comment->getCreatedAt(), false) . '</h6>
                                </div>
                                <div class="comment_action">
                                    <a href="#" class="btn btn-info btn-sm">Modifier</a>
                                    <a href="#" class="btn btn-danger btn-sm">Supprimer</a>
                                </div>
                            </div>
                            <div class="post-description">
                               '. ($comment->getContenu()) .'
                            </div>
                        </div>
                ';
            }

            $data = json_encode($html);
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent($data);
        }
        return $response;
    }
}
