<?php

namespace Fmaj\LaposteDatanovaBundle\Parser;

interface ParserInterface
{
    /**
     * @param string $dataset
     *
     * @return false|array
     */
    public function parse($dataset);
}
