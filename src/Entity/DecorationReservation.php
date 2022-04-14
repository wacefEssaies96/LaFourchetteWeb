<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DecorationReservation
 *
 * @ORM\Table(name="decoration_reservation", indexes={@ORM\Index(name="fk_dec", columns={"IdD"}), @ORM\Index(name="fk_res", columns={"IdR"})})
 * @ORM\Entity(repositoryClass="App\Repository\DecorationReservationRepository")
 */
class DecorationReservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdDR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddr;

    /**
     * @var \Decoration
     *
     * @ORM\ManyToOne(targetEntity="Decoration",inversedBy="DecorationReservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdD", referencedColumnName="IdD")
     * })
     */
    private $idd;

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation",inversedBy="DecorationReservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdR", referencedColumnName="IdR")
     * })
     */
    private $idr;

    public function getIddr(): ?int
    {
        return $this->iddr;
    }

    public function getIdd(): ?Decoration
    {
        return $this->idd;
    }

    public function setIdd(?Decoration $idd): self
    {
        $this->idd = $idd;

        return $this;
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


}
