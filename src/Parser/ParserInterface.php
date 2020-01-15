<?php

namespace Fmaj\LaposteDatanovaBundle\Parser;

interface ParserInterface
{
    /**
     * @return false|array
     */
    public function parse(string $dataset);
}
