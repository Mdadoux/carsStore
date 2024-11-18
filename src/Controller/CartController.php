<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(SessionInterface $session, ProductRepository $productRepository): Response
    {
        //Récuperer le panier depuis la session
        $cart = $session->get('cart', []);
        $total = 0;
        $cartWithDatas = [];
        if (!empty($cart)) {
            foreach ($cart as $id => $quantity) {
                //on recupère le produit
                $product = $productRepository->find($id);
                $cartWithDatas[] = [
                    'product' => $product,
                    'qty' => $quantity
                ];
                // Calculer le prix total
                $total += $product->getPrice() * $quantity;
            }
        }

        return $this->render('cart/index.html.twig', [
            'datas' => $cartWithDatas,
            'cartTotal' => $total
        ]);
    }

    //ajouter dans le panier
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id, SessionInterface $session)
    {
        // recuper le panier depuis la session
        $cart = $session->get('cart', []);
        //Le produit est dejà dans le panier, on augmente juste la quantité
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('app_cart');
    }
    #[Route('/cart/remove/{id}',name: 'cart_remove')]
    public function remove(int $id,SessionInterface $session)
    {
        $cart = $session->get('cart',[]);
        if (!empty($cart[$id])){
            unset($cart[$id]);
        }
        $session->set('cart',$cart);
        return $this->redirectToRoute('app_cart');
    }
}
