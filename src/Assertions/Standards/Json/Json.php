<?php

namespace Mashbo\Components\ApiKit\Assertions\Standards\Json;

use Mashbo\Components\ApiKit\Assertions\Exceptions\AssertionException;
use Mashbo\Components\ApiKit\Assertions\Standards\Json\Exceptions\InvalidJsonAssertionException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class Json
{
    public static function assertIsValid($string)
    {
        self::decode($string);
    }

    public static function assertKeyEquals($json, $key, $expected)
    {
        $propertyAccessor = self::getAccessor();

        $data = self::decode($json);
        $actual = $propertyAccessor->getValue($data, $key);

        if ($actual !== $expected) {
            throw new AssertionException("Expected " . json_encode($expected, JSON_PRETTY_PRINT) ." but found " . json_encode($actual, JSON_PRETTY_PRINT));
        }
    }

    private static function decode($string)
    {
        $data = json_decode($string, true);
        $lastError = json_last_error();
        if (JSON_ERROR_NONE !== $lastError) {
            throw new InvalidJsonAssertionException($lastError, json_last_error_msg(), $string);
        }
        return $data;
    }

    public static function assertKeyExists($json, $key)
    {
        self::getAccessor()->getValue(
            self::decode($json),
            $key
        );
    }

    public static function assertKeyNotEmpty($json, $key)
    {
        $value = self::getAccessor()->getValue(
            self::decode($json),
            $key
        );
        if (empty($value)) {
            throw new \LogicException("Found empty value at $key");
        }
    }

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private static function getAccessor()
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
            ->getPropertyAccessor();
        return $propertyAccessor;
    }
}