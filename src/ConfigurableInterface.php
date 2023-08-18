<?php

namespace Swiftly\Template;

/**
 * Interface for classes that can be passed configuration flags
 *
 * Currently only used in combination with certain escape contexts, for instance
 * with the JsonEscaper to pass flags directly to `json_encode`. In the future
 * however it might be useful elsewhere.
 */
interface ConfigurableInterface
{
    /**
     * Pass configuration flags into the object
     *
     * Implementors should pass back the same object, so other methods can be
     * called.
     *
     * @param int $flags Configuration flags
     * @return self      Chainable
     */
    public function with(int $flags) /* : self */;
}
