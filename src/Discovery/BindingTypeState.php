<?php

/*
 * This file is part of the puli/repository-manager package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\RepositoryManager\Discovery;

use Puli\RepositoryManager\Discovery\Store\BindingTypeStore;
use Puli\RepositoryManager\Package\Package;
use Puli\RepositoryManager\Package\RootPackage;

/**
 * Contains constants representing the state of a binding type.
 *
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
final class BindingTypeState
{
    /**
     * State: The type is not loaded.
     */
    const UNLOADED = 0;

    /**
     * State: The type is enabled.
     */
    const ENABLED = 1;

    /**
     * State: The binding is disabled because it was defined twice or more.
     */
    const DUPLICATE = 2;

    /**
     * Returns all states.
     *
     * @return int[] The states.
     */
    public static function all()
    {
        return array(
            self::UNLOADED,
            self::ENABLED,
            self::DUPLICATE
        );
    }

    /**
     * Detects the state of a binding type.
     *
     * @param BindingTypeDescriptor $typeDescriptor The binding.
     * @param BindingTypeStore      $typeStore      The store with the defined
     *                                              types.
     *
     * @return int The state.
     */
    public static function detect(BindingTypeDescriptor $typeDescriptor, BindingTypeStore $typeStore)
    {
        $typeName = $typeDescriptor->getName();

        if (!$typeStore->existsAny($typeName)) {
            return self::UNLOADED;
        }

        if ($typeStore->isDuplicate($typeName)) {
            return self::DUPLICATE;
        }

        return self::ENABLED;
    }

    /**
     * Must not be instantiated.
     */
    private function __construct() {}
}