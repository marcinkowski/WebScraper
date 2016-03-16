<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetCommand
 *
 * @package ApiBundle\Command
 */
class ProductsCommand extends ContainerAwareCommand
{
    /**
     * Configuration
     */
    protected function configure()
    {
        $this
            ->setName('api:products')
            ->setDescription('Get list of products in JSON format.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $products = $this->getContainer()->get('api.products.web_client')->getProductsByCategory('fruits');

        $output->writeln(json_encode($products));
    }
}