<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admini', name: 'admin.dashboard')]
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, OrderRepository $orderRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findAll();
        return $this->render('admin/index.html.twig', [
            'categories' => $categories,
            'products' => $products,
            'orders' => $orderRepository->findAll()
        ]);
    }

    #[Route('/admini/orders', 'admin.orders')]
    public function order(OrderRepository $orderRepository) 
    {
        $orders = $orderRepository->findAll();
        return $this->render('admin/orders/index.html.twig', [
            'orders' => $orders
        ]);
    }
}
