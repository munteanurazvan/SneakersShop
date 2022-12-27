<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Form\Product1Type;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/crud/product")
 */
class CrudProductController extends AbstractController
{
    /**
     * @Route("/", name="app_crud_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('crud_product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories'=>$categoryRepository->findAll ()

        ]);
    }

    /**
     * @Route("/remove-image/{id}", name="app_crud_remove_image")
     */
    public function removeImage(ProductImage $image, Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($image);
        $entityManager->flush();
        return $this->redirectToRoute('app_crud_product_index',
            [
                'categories'=>$categoryRepository->findAll ()
            ], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/new", name="app_crud_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            foreach ($imageFiles as $imageFile) {
                $imageFile->move ('/var/www/html/munteanuR/SneakersShop/public/images', $imageFile->getClientOriginalName ());
                $productImage = new ProductImage();
                $productImage->setImage ($imageFile->getClientOriginalName());
                $productImage->setProduct ($product);
            }
            $productRepository->add($product);
            return $this->redirectToRoute('app_crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findAll ()
        ]);
    }

    /**
     * @Route("/{id}", name="app_crud_product_show", methods={"GET"})
     */
    public function show(Product $product, CategoryRepository $categoryRepository): Response
    {
        return $this->render('crud_product/show.html.twig', [
            'product' => $product,
            'categories'=>$categoryRepository->findAll ()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_crud_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
            foreach ($imageFiles as $imageFile) {
                $imageFile->move ('/var/www/html/munteanuR/SneakersShop/public/images', $imageFile->getClientOriginalName ());
                $productImage = new ProductImage();
                $productImage->setImage ($imageFile->getClientOriginalName());
                $productImage->setProduct ($product);
            }
            $productRepository->add($product);
            return $this->redirectToRoute('app_crud_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crud_product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findAll ()

        ]);
    }

    /**
     * @Route("/{id}", name="app_crud_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product);
        }

        return $this->redirectToRoute('app_crud_product_index',
            [
                'categories'=>$categoryRepository->findAll ()
            ], Response::HTTP_SEE_OTHER);
    }
}
