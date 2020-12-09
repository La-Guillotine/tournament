<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait AddressTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $address = "";


    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}