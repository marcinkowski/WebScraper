<?php

namespace ApiBundle\Services\ProductsWebClient;

/**
 * Interface ProductsWebClientInterface
 *
 * @package ApiBundle\Services\ProductsWebClient
 */
interface ProductsWebClientInterface
{
    public function getProductsByCategory($category);
}