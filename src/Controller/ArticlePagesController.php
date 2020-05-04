<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticlePagesController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('articles_pages/index.html.twig', [
            'controller_name' => 'ArticlePagesController',
        ]);
    }

    /**
     * @Route("/category/{slug}", name="article_categories")
     */
    public function categories(ArticleRepository $articleRepository, string $slug)
    {
        $articles = $articleRepository->findArticlesByCategorySlug($slug);
        return $this->render('articles_pages/categories.html.twig', [
            'title'=>$slug,
            'articles'=>$articles
        ]);
    }

    /**
     * @Route("/{slug}", name="article_view")
     */
    public  function articleView(string $slug, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->findOneBy(['slug'=>$slug]);
//        dd($article);
        $article ? $title = $article->getTitle() : $title='';
        return $this->render('articles_pages/article.html.twig',[
            'title'=>$title,
            'article'=>$article,
        ]);
    }
}
