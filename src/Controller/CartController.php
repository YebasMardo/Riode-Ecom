<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', 'cart.add', methods: ['POST'])]
    public function add(int $id, CartService $cartService)
    {
        $cartService->add($id);
        $cart = $cartService->getCart();
        $totalQuantity = array_sum($cart);
        return $this->json([
            'message' => 'Produit ajouté au panier',
            'totalQuantity' => $totalQuantity,
            'cart' => $cart
        ]);
    }

    #[Route('/get', methods: ['GET'])]
    public function getTotalCount(CartService $cartService)
    {
        $cart = $cartService->getCart();
        $totalQuantity = array_sum($cart);

        return $this->json([
            'totalQuantity' => $totalQuantity
        ]);
    }

    #[Route('/cart', 'cart.show')]
    public function show(CartService $cartService, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $cart = $cartService->getCartProducts();
        dd($cart);
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'categories' => $categories
        ]);
    }

    #[Route('/cart/partial', 'cart.partial')]
    public function CartPartial(CartService $cartService)
    {
        $cart = $cartService->getCartProducts();

        return $this->render('cart/cart_partial.html.twig', [
            'cart' => $cart
        ]);
    }
}
