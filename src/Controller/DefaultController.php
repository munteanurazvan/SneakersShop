<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductSearchType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render ('default/index.html.twig',
            [
                'categories' => $categoryRepository->findAll ()
            ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(CategoryRepository $categoryRepository): Response
    {
        return $this->render ('default/contact.html.twig',
            [
                'categories' => $categoryRepository->findAll (),
            ]);
    }

    /**
     * @Route("/category/{category}", name="category")
     */
    public function category(Category $category, Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $form = $this->createForm (ProductSearchType::class);

        $form->handleRequest ($request);
        if ($form->isSubmitted () && $form->isValid ()) {
            $data = $form->getData ();

            foreach ($data as $key => $value){
                if (is_null($value)){
                    unset($data[$key]);
                }
            }
            $qb = $productRepository->createQueryBuilder ('p');

            if ($data['categories']->count()>0) {
                $qb->andWhere ('p.category in (:categories)')
                    ->setParameter ('categories', $data['categories']);
            }

            if (isset($data['name'])){
                $qb->andWhere ('p.name like :name')
                    ->setParameter ('name', '%'.$data['name'].'%');
            }

            if ($data['price_range']>0){
                foreach ($data['price_range'] as $range){
                    $values = explode ('-', $range);
                    $qb->andWhere ("p.price BETWEEN :start$key and :end$key")
                        ->setParameter ("start$key", $values[0])
                        ->setParameter ("end$key", $values[1]);
                }
            }

            $products = $qb->getQuery ()->getResult();
        } else {
            $products = $category->getProducts ();
        }
            return $this->render ('default/category.html.twig',
                [
                    'category' => $category,
                    'products' => $products,
                    'categories' => $categoryRepository->findAll (),
                    'form' => $form->createView ()
                ]);
        }


        /**
         * @Route("/product/{product}", name="product")
         */
        public function product(Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
        {
            return $this->render ('default/product.html.twig',
                [
                    'categories' => $categoryRepository->findAll (),
                    'product' => $product
                ]);
        }
}
