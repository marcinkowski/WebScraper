<?php

namespace ApiBundle\Services\ProductsWebClient;


use ApiBundle\Documents\ProductDocument;
use ApiBundle\Documents\ProductsCollection;
use Symfony\Component\DomCrawler\Crawler;

class ProductsWebClientTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $httpClientStub = $this->getMockBuilder('Goutte\Client')
            ->getMock();

        $httpClientStub->expects($this->at(0))
            ->method('request')
            ->with('GET', 'http://test.test')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/products.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(1))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-apricot-ripe---ready-320g.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(2))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-avocado-xl-pinkerton-loose-300g.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(3))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-avocado--ripe---ready-x2.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(4))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-avocados--ripe---ready-x4.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(5))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-conference-pears--ripe---ready-x4-%28minimum%29.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(6))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-golden-kiwi--taste-the-difference-x4-685641-p-44.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));
        $httpClientStub->expects($this->at(7))
            ->method('request')
            ->with('GET', 'http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-kiwi-fruit--ripe---ready-x4.html')
            ->willReturn(new Crawler(file_get_contents(__DIR__.'/product.mock.html'), 'http://test.test'));


        $this->client = new ProductsWebClient($httpClientStub, ['test' => 'http://test.test']);
    }

    public function testGetProductsByCategory()
    {
        $collection = new ProductsCollection();
        $collection->append($this->createProduct('Sainsbury\'s Apricot Ripe & Ready x5', 3.5));
        $collection->append($this->createProduct('Sainsbury\'s Avocado Ripe & Ready XL Loose 300g', 1.5));
        $collection->append($this->createProduct('Sainsbury\'s Avocado, Ripe & Ready x2', 1.8));
        $collection->append($this->createProduct('Sainsbury\'s Avocados, Ripe & Ready x4', 3.2));
        $collection->append($this->createProduct('Sainsbury\'s Conference Pears, Ripe & Ready x4 (minimum)', 1.5));
        $collection->append($this->createProduct('Sainsbury\'s Golden Kiwi x4', 1.8));
        $collection->append($this->createProduct('Sainsbury\'s Kiwi Fruit, Ripe & Ready x4', 1.8));


        $this->assertEquals($collection, $this->client->getProductsByCategory('test'));
    }

    private function createProduct($title, $price)
    {
        $document = new ProductDocument();
        $document->setTitle($title);
        $document->setPrice($price);

        //We mock only one page for product page so everywhere size and description are the same.
        $document->setSize('39.69Kb');
        $document->setDescription('Avocados');

        return $document;
    }
}