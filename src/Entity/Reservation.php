<?php

namespace App\Entity;

use App\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fk_idU_RS", columns={"IdU"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\OneToMany(targetEntity="DecorationReservation",mappedBy="Reservation")
     * @ORM\Column(name="IdR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idr;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU", nullable=false)
     * })
     */
    private $idu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateCreation", type="datetime", nullable=false)
     */
    private $datecreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DateModification", type="datetime", nullable=false)
     */
    private $datemodification;
    

    public function getIdr(): ?int
    {
        return $this->idr;
    }
    public function setIdr( ?int $idr ): self
    {
        $this->idr=$idr;
        return $this;
    }

    public function getIdu(): ?Utilisateur
    {
        return $this->idu;
    }

    public function setIdu(?Utilisateur $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatemodification(): ?\DateTimeInterface
    {
        return $this->datemodification;
    }

    public function setDatemodification(\DateTimeInterface $datemodification): self
    {
        $this->datemodification = $datemodification;

        return $this;
    }


}
