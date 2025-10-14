<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\CheckoutType;
use App\Repository\CategoryRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CheckoutController extends AbstractController
{
    #[Route('/cart/checkout', name: 'cart.checkout')]
    public function index(Request $request, CartService $cartService, EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $cartProducts = $cartService->getCartProducts();
        $total = $cartService->getTotal();

        $form = $this->createForm(CheckoutType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $order = new Order();

            if($this->getUser()) {
                $order->setUser($this->getUser());
            }
            $order->setFirstname($data['firstname']);
            $order->setLastname($data['lastname']);
            $order->setEmail($data['email']);
            $order->setPhone($data['phone']);
            $order->setCountry($data['country']);
            $order->setCity($data['city']);
            $order->setPostalCode($data['postalcode']);
            $order->setAddress($data['address']);
            $order->setProducts($cartProducts); 
            $order->setTotal($total);
            $order->setCreatedAt(new \DateTimeImmutable());

            $em->persist($order);
            $em->flush();

            $cartService->clear();

            return $this->redirectToRoute('home.index');
        }
        return $this->render('cart/checkout.html.twig', [
            'form' => $form->createView(),
            'cart' => $cartProducts,
            'totalPrice' => $total,
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
