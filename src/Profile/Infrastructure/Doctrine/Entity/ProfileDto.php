<?php

namespace Profile\Infrastructure\Doctrine\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProfileDto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $token;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    public $serialized;
}
