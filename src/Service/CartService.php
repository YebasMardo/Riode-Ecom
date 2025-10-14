<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use PhpParser\Node\Stmt\Foreach_;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;

    public function __construct(RequestStack $requestStack, private ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
    }

    public function add(int $productId, int $quantity = 1)
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$productId])) {
            $cart[$productId] = 0;
        }

        $cart[$productId] += $quantity;

        $this->session->set('cart', $cart);
    }

    public function remove(int $productID)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$productID]);
        $this->session->set('cart', $cart);
    }

    public function getCart()
    {
        return $this->session->get('cart', []);
    }

    public function getCartProducts(): array
    {
        $cart = $this->getCart();
        $products = [];
        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $products[] = [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'price' => $product->getPromoPrice(),
                    'images' => $product->getProductImages(),
                    'quantity' => $quantity
                ];
            }
        }
        return $products;
    }

    public function getTotal(): float
    {
        $cart = $this->session->get('cart', []);
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepository->find($productId);
            if ($product) {
                $total += $product->getPrice() * $quantity;
            }
        }
        return $total;
    }

    public function clear()
    {
        $this->session->remove('cart');
    }
}
