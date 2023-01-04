<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductSearchType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/cat")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/create", name="category_form")
     */
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm (CategoryType::class, $category);

        $categoryForm->handleRequest ($request);
        if($categoryForm->isSubmitted () && $categoryForm->isValid ()){
            $imageFile = $categoryForm->get('file')->getData();
            $imageFile->move('/var/www/html/munteanuR/SneakersShop/public/images', $imageFile->getClientOriginalName());
            $category->setImage ($imageFile->getClientOriginalName());

            $em = $this->getDoctrine ()->getManager ();
            $em->persist ($category);
            $em->flush ();
        }

        return $this->render('category/index.html.twig',
        [
            'categoryForm' => $categoryForm->createView (),
            'categories'=>$categoryRepository->findAll ()

        ]);
    }

    /**
     * @Route("/edit/{category}", name="category_edit")
     */
    public function edit(Category $category, Request $request, CategoryRepository $categoryRepository): Response
    {
        $categoryForm = $this->createForm (CategoryType::class, $category);

        $categoryForm->handleRequest ($request);
        if($categoryForm->isSubmitted () && $categoryForm->isValid ()){
            $imageFile = $categoryForm->get('file')->getData();
            $imageFile->move('/var/www/html/munteanuR/SneakersShop/public/images', $imageFile->getClientOriginalName());
            $category->setImage ($imageFile->getClientOriginalName());

            $em = $this->getDoctrine ()->getManager ();
            $em->persist ($category);
            $em->flush ();
        }

        return $this->render('category/index.html.twig',
            [
                'categoryForm' => $categoryForm->createView (),
                'categories'=>$categoryRepository->findAll ()

            ]);
    }
}
