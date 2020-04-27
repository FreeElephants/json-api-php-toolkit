<?php

namespace FreeElephants\JsonApiToolkit\Neomerx;

use Doctrine\ORM\EntityManagerInterface;
use Neomerx\JsonApi\Contracts\Factories\FactoryInterface;
use Neomerx\JsonApi\Encoder\Encoder as NeomerxEncoder;

class Encoder extends NeomerxEncoder
{
    /**
     * @var EntityManagerInterface
     */
    private static $entityManager;

    public static function setEntityManager(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    protected static function createFactory(): FactoryInterface
    {
        return new Factory(self::$entityManager);
    }
}
