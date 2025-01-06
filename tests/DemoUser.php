<?php

declare(strict_types=1);

namespace HyperfTest;

use Qbhy\HyperfAuth\Authenticatable;

class DemoUser implements Authenticatable
{
    public $id;

    /**
     * DemoUser constructor.
     * @param mixed $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function retrieveById($key): ?Authenticatable
    {
        return new DemoUser($key);
    }
}
