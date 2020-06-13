<?php

namespace Incapsula\API\Parameters;

use Incapsula\API\Interfaces\Parameter;

class Pagination implements Parameter
{
    private $pageSize;
    private $pageNumber;

    public function __construct(int $pageSize = 50, int $pageNumber = 0)
    {
        $this->pageSize     = $pageSize;
        $this->pageNumber   = $pageNumber;
    }
    
    public function getRequestParameters(): array
    {
        return [
            'page_size' => $this->pageSize,
            'page_num'  => $this->pageNumber
        ];
    }
}
