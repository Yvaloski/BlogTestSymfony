<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(Article::class);

        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }


    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', ['title' => "Bienvenu", 'age' => 35]);
    }


    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit', name: 'blog_edit')]
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager )
    {
        if (!$article) {
            $article = new Article();
        }
    

        /*  $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm(); */


        $form = $this->createForm(ArticleType::class,$article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTimeImmutable());
            }
            
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>  $article->getId()]);
        }

        return $this->render('blog/create.html.twig',['formArticle' => $form->createView(),
        "editMode"=> $article->getId()!== null]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Article $article, Request $request, EntityManagerInterface $manager )
    { 
        $comment = new Comment();


        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
                $comment->setCreatedAt(new \DateTime());
                $manager->persist($comment);
                $manager->flush();
            
            return $this->redirectToRoute('blog_show',['id'=> $article->getId()]);
        }
    
        return $this->render('blog/show.html.twig',[
            "article"=>$article,
            'commentForm'=> $form->createView()
        
        ]);
    }




}
