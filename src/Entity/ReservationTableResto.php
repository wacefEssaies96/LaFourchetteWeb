<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationTableResto
 *
 * @ORM\Table(name="reservation_table_resto", indexes={@ORM\Index(name="fk_idR_RTR", columns={"IdR"}), @ORM\Index(name="fk_idT_RTR", columns={"IdT"})})
 * @ORM\Entity
 */
class ReservationTableResto
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdRTR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrtr;

    /**
     * @var int
     *
     * @ORM\Column(name="IdR", type="integer", nullable=false)
     */
    private $idr;

    /**
     * @var int
     *
     * @ORM\Column(name="IdT", type="integer", nullable=false)
     */
    private $idt;

    public function getIdrtr(): ?int
    {
        return $this->idrtr;
    }

    public function getIdr(): ?int
    {
        return $this->idr;
    }

    public function setIdr(int $idr): self
    {
        $this->idr = $idr;

        return $this;
    }

    public function getIdt(): ?int
    {
        return $this->idt;
    }

    public function setIdt(int $idt): self
    {
        $this->idt = $idt;

        return $this;
    }


}
