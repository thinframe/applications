<?php

namespace ThinFrame\Applications;

/**
 * Class ReverseArrayIterator
 *
 * @package ThinFrame\Applications\DependencyInjection\Test
 */
class ReverseIterator extends \ArrayIterator
{
    /**
     * Constructor
     *
     * @param \Iterator $iterator
     */
    public function __construct(\Iterator $iterator)
    {
        parent::__construct(array_reverse(iterator_to_array($iterator)));
    }
}
