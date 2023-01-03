<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('default/index.html.twig',
            [
                'categories'=>$categoryRepository->findAll ()
            ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(CategoryRepository $categoryRepository): Response
    {
        return $this->render('default/contact.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
            ]);
    }

    /**
     * @Route("/category/{category}", name="category")
     */
    public function category(Category $category, CategoryRepository $categoryRepository): Response
    {
        return $this->render('default/category.html.twig',
        [
            'category'=>$category,
            'categories'=>$categoryRepository->findAll ()

        ]);
    }
    /**
     * @Route("/product/{product}", name="product")
     */
    public function product(Product $product,ProductRepository $productRepository,CategoryRepository $categoryRepository): Response
    {
        return $this->render('default/product.html.twig',
            [
                'categories'=>$categoryRepository->findAll(),
                'product'=>$product

            ]);
    }
}
