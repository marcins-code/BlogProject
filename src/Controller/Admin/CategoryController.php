<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Helper\ButtonClickRedirect;

/**
 * @Route("/admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET"})
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
     $query = $categoryRepository->findBy([], ['id'=>'ASC'])   ;
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('category/index.html.twig', [
            'pagination' => $pagination,
            'title'=>'Categories'
        ]);
    }

    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, ButtonClickRedirect $clickRedirect): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            if ($form->get('save')->isClicked()) {
                return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
            }


            if ($form->get('save_close')->isClicked()) {

                return $this->redirectToRoute('category_index');
            }

            if ($form->get('save_new')->isClicked()) {

                return $this->redirectToRoute('category_new');
            }
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'New category'
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($form->get('save_exit')->isClicked()) {

                return $this->redirectToRoute('category_index');
            }

            if ($form->get('save_new')->isClicked()) {

                return $this->redirectToRoute('category_new');
            }
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Edit category'
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
