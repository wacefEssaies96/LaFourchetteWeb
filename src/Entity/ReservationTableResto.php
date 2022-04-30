<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReservationTableResto
 *
 * @ORM\Table(name="reservation_table_resto", indexes={@ORM\Index(name="fk_idR_RTR", columns={"IdR"}), @ORM\Index(name="fk_idT_RTR", columns={"IdT"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReservationTableRepository")
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
     * @var Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation",inversedBy="ReservationTableResto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdR", referencedColumnName="IdR")
     * })
     */
    private $idr;

    /**
     * @var TableResto
     *
     * @ORM\ManyToOne(targetEntity="TableResto",inversedBy="ReservationTableResto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdT", referencedColumnName="IdT")
     * })
     */
    private $idt;

    public function getIdrtr(): ?int
    {
        return $this->idrtr;
    }

    public function getIdr(): ?Reservation
    {
        return $this->idr;
    }

    public function setIdr(?Reservation $idr): self
    {
        $this->idr = $idr;

        return $this;
    }

    public function getIdt(): ?TableResto
    {
        return $this->idt;
    }

    public function setIdt(?TableResto $idt): self
    {
        $this->idt = $idt;

        return $this;
    }


}
