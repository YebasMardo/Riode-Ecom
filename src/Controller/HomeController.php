<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductImageRepository;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\WishListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, CartService $cartService, WishListService $wishListService): Response
    {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();
        $cart = $cartService->getCartProducts();
        $wishList = $wishListService->getWishListProducts();
        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'cart' => $cart,
            'wishList' => $wishList,
        ]);
    }

    #[Route('/shop', 'home.shop')]
    public function shop(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $categoryRepository->findAll();
        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    #[Route('/show/{id}', 'home.show')]
    public function show(Product $product, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('home/show.html.twig', [
            'product' => $product,
            'categories' => $categories
        ]);
    }
}
