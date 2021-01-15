<?php

namespace app\common\traits;

/**
 * @descption 单例模式
 * Trait SingletonTrait
 * @package app\common\traits
 */
trait SingletonTrait
{
    private static $instance = null;

    public function __construct()
    {
    }

    /**
     * @return null
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}