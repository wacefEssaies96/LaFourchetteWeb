<?php

namespace App\Entity;

use App\Repository\DatetimetrTableRestoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DatetimetrTableRestoRepository::class)
 */
class DatetimetrTableResto
{
    /**
     * @var int
     * @ORM\Column(name="idDTR", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idDTR;

    /**
     * @var TableResto
     *
     * @ORM\ManyToOne(targetEntity="TableResto",inversedBy="DatetimetrTableResto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="IdT", referencedColumnName="IdT")
     * })
     */
    private $idt;
    
    /**
     * @var Datetimetr
     *
     * 
     * @Assert\Unique
     * @ORM\ManyToOne(targetEntity="Datetimetr",inversedBy="DatetimetrTableResto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iddt", referencedColumnName="iddt")
     * })
     */
    private $iddt;

    public function getIdDTR(): ?int
    {
        return $this->idDTR;
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
    public function getIddt(): ?Datetimetr
    {
        return $this->iddt;
    }

    public function setIddt(?Datetimetr $iddt): self
    {
        $this->iddt = $iddt;

        return $this;
    }
}
