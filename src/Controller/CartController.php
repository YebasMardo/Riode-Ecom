<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', 'cart.add', methods: ['POST'])]
    public function add(int $id, Request $request, CartService $cartService)
    {
        $data = json_decode($request->getContent(), true);
        $quantity = $data['quantity'] ?? 1;
        $cartService->add($id, $quantity);
        $cart = $cartService->getCart();
        $totalQuantity = array_sum($cart);
        return $this->json([
            'message' => 'Produit ajouté au panier',
            'totalQuantity' => $totalQuantity,
            'cart' => $cart,
            'totalPrice' => $cartService->getTotal()
        ]);
    }

    #[Route('/cart/remove/{id}', 'cart.remove', methods: ['DELETE'])]
    public function remove(int $id, CartService $cartService)
    {
        $cartService->remove($id);
        return $this->json(['success' => true]);
    }

    #[Route('/get', methods: ['GET'])]
    public function getTotalCount(CartService $cartService)
    {
        $cart = $cartService->getCart();
        $totalQuantity = array_sum($cart);
        return $this->json([
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $cartService->getTotal()
        ]);
    }

    #[Route('/cart', 'cart.show')]
    public function show(CartService $cartService, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $cart = $cartService->getCartProducts();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'categories' => $categories,
            'total' => $total
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
