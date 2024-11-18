<?php

namespace App\Services\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    protected $session;
    protected $productRepository;
    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }
    public function add(int $id)
    {
        // recuper le panier depuis la session
        $cart = $this->session->get('cart', []);
        //Le produit est dejà dans le panier, on augmente juste la quantité
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);

    }
    public function remove(int $id)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->session->set('cart', $cart);
    }

    public function getTotalCart(): float
    {
        $total = 0;
        foreach ($this->getCartWithDatas() as $data) {
            $total += $data['product']->getPrice() * $data['quantity'];
        }
        return $total;
    }

    public function getCartWithDatas(): array
    {
        //Récuperer le panier depuis la session
        $cart = $this->session->get('cart', []);
        $cartWithDatas = [];
        if (!empty($cart)) {
            foreach ($cart as $id => $quantity) {
                //on recupère le produit
                $product = $this->productRepository->find($id);
                $cartWithDatas[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartWithDatas;
    }


}