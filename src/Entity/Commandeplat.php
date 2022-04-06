<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commandeplat
 *
 * @ORM\Table(name="commandeplat", indexes={@ORM\Index(name="fk_reference", columns={"reference"}), @ORM\Index(name="fk_idC", columns={"idC"})})
 * @ORM\Entity
 */
class Commandeplat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idC", type="integer", nullable=false)
     */
    private $idc;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=50, nullable=false)
     */
    private $reference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdc(): ?int
    {
        return $this->idc;
    }

    public function setIdc(int $idc): self
    {
        $this->idc = $idc;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }


}
