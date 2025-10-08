<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, ProductImageRepository $productImageRepository): Response
    {
        $categories = $categoryRepository->findAllWithCount();
        $products = $productRepository->findAll();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }

    #[Route('/shop', 'home.shop')]
    public function shop(ProductRepository $productRepository) 
    {
        $products = $productRepository->findAll();
        return $this->render('home/shop.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/show', 'home.show')]
    public function show() 
    {
        return $this->render('home/show.html.twig', []);
    }
}
