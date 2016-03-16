<?php

namespace ApiBundle\Controller;

/**
 * Class ProductsController
 *
 * @package ApiBundle\Controller
 */
class ProductsController extends BaseController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction()
    {
        $products = $this->get('api.products.web_client')->getProductsByCategory('fruits');

        return $this->response($products);
    }
}