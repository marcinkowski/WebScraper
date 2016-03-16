<?php

namespace ApiBundle\Services\ProductsWebClient;

use ApiBundle\Documents\ProductDocument;
use ApiBundle\Documents\ProductsCollection;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ProductsWebClient
 *
 * @package ApiBundle\Services\ProductsWebClient
 */
class ProductsWebClient implements ProductsWebClientInterface
{
    /**
     * @var array
     */
    private $categories;

    /**
     * @var Client
     */
    private $client;

    /**
     * ProductsWebClient constructor.
     *
     * @param Client  $client     Web Browser Client.
     * @param array   $categories Array with categories and urls to web pages.
     */
    public function __construct(Client $client, array $categories)
    {
        $this->client = $client;
        $this->categories = $categories;
    }

    /**
     * @param $category
     *
     * @return ProductsCollection
     */
    public function getProductsByCategory($category)
    {
        // ProductsCollection and ProductDocument are simple collection objects used instead of arrays.
        $collection = new ProductsCollection();

        $crawler = $this->sendRequest($this->getCategoryUrl($category));

        $crawler->filter('.product')->each(function (Crawler $row) use ($collection) {
            $product = new ProductDocument();
            $product->setTitle($row->filter('.productInfo > h3')->text());
            $product->setPrice($this->formatPrice($row->filter('.pricePerUnit')->text()));

            $productPageUri = $row->filter('.productInfo > h3 > a')->link()->getUri();

            if (!empty($productPageUri)) {
                $productPage = $this->sendRequest($productPageUri);
                $product->setDescription($productPage->filter('.productText')->first()->text());
                $product->setSize($this->getStringSize($productPage->html()));
            }

            $collection->append($product);
        });


        return $collection;
    }

    /**
     * @param $string
     *
     * @return string
     */
    private function getStringSize($string)
    {
        return sprintf('%sKb', round(mb_strlen($string, '8bit')/1024, 2));
    }

    /**
     * @param string $price
     *
     * @return float
     */
    private function formatPrice($price)
    {
        // convert "," to "."
        $price = str_replace(',', '.', $price);
        // remove everything except numbers and dot "."
        $price = preg_replace("/[^0-9\.]/", "", $price);
        // remove all seperators from first part and keep the end
        $price = str_replace('.', '',substr($price, 0, -3)) . substr($price, -3);

        return (float) $price;
    }

    /**
     * @param string $category
     *
     * @return string
     */
    private function getCategoryUrl($category)
    {
        if (!isset($this->categories[$category])) {
            throw new \BadMethodCallException(sprintf("Category \"%s\" called in %s doesn't exists.", $category, __METHOD__));
        }

        return $this->categories[$category];
    }

    /**
     * @param string $url    Url to send request
     * @param string $method HTTP Method
     *
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function sendRequest($url, $method='GET')
    {
        return $this->client->request($method, $url);
    }
}