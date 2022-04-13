<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeRec
 *
 * @ORM\Table(name="type_rec")
 * @ORM\Entity(repositoryClass="App\Repository\TypeRecRepository")
 */
class TypeRec
{
    /**
     * @var string
     *
     * @ORM\Column(name="typeRec", type="string", length=30, nullable=false)
     * @ORM\Id
     */
    private $typerec;

    /**
     * @var string
     *
     * @ORM\Column(name="refT", type="string", length=255, nullable=false)
     */
    private $reft;

    public function getTyperec(): ?string
    {
        return $this->typerec;
    }

    public function getReft(): ?string
    {
        return $this->reft;
    }
    public function setTyperec(string $typerec): self
    {
        $this->typerec = $typerec;

        return $this;
    }
    public function setReft(string $reft): self
    {
        $this->reft = $reft;

        return $this;
    }


}