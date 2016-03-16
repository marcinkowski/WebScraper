<?php

namespace ApiBundle\Documents;

/**
 * Class ProductsCollection
 *
 * @package ApiBundle\Documents
 */
class ProductsCollection extends \ArrayObject implements \JsonSerializable
{
    /**
     * @var int
     */
    private $total;

    /**
     * @return int
     */
    public function getTotal()
    {
        if ($this->total === null) {
            foreach ($this as $document) {
                $this->total += $document->getPrice();
            }
        }

        return $this->total;
    }

    /**
     * @return array
     */
    function jsonSerialize()
    {
        $result = [
            'results' => [],
            'total' => $this->getTotal()
        ];

        foreach ($this as $document) {
            $result['results'][] = $document->jsonSerialize();
        }

        return $result;
    }
}