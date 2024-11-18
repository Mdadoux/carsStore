<?php

namespace App\Controller;


use App\Services\Cart\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'datas' => $cartService->getCartWithDatas(),
            'cartTotal' => $cartService->getTotalCart()
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id, CartService $cartService)
    {
        $cartService->add($id);
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/remove/{id}',name: 'cart_remove')]
    public function remove(int $id,CartService $cartService)
    {
        $cartService->remove($id);
        return $this->redirectToRoute('app_cart');
    }
}
