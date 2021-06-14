<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Etablissement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{
    private $session;
    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @Route("/profile/{etablissement_id}", name="home_auth")
     */
    public function home_auth($etablissement_id, Request $request):Response
    {

        if(!$this->getUser()){
            return $this->redirectToRoute("app_login");
        }
        
        $session = $request->getSession();
        $current_session = $session->get("session");
        $etablissement = $current_session["etablissement"];
        
        $repoArticle = $this->getDoctrine()->getRepository(Article::class);
        
        $articles = $repoArticle->findBy(["etablissement" => $etablissement], ["createdAt" => "DESC"], 6);
        //dd($articles);
        $user = $this->getUser();
        //dd($user);
        return $this->render("front/home.html.twig", [
            "articles" => $articles,
            "etablissement"=>$etablissement,
        ]);  
    }

    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render("front/home_not_auth.html.twig", [
                
        ]);
    }

    

    /**
     * @Route("/user_register", name="user_register")
     */
    public function user_register():Response
    {   
        return $this->render("security/user_register.html.twig");
    }

    /**
     * @Route("/test_ajout_et", name="test_ajout_et")
     */
    public function test_ajout_et():Response
    {   
        return $this->render("security/test.html.twig");   
    } 
}
