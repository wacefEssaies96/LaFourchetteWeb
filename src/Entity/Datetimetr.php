<?php

namespace App\Entity;

use App\Repository\DatetimetrRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DatetimetrRepository::class)
 */
class Datetimetr
{
    /**
     * @var int
     *
     * @ORM\Column(name="iddt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="DatetimetrTableResto",mappedBy="Datetimetr")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $iddt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    
    /**
     * @var string
     *
     * @ORM\Column(name="Etat", type="string", length=20, nullable=false)
     * @Assert\NotBlank(message="Le Champ Etat est obligatoire")
     */
    private $etat;

    public function getIddt(): ?int
    {
        return $this->iddt;
    }

    public function setIddt(int $iddt): self
    {
        $this->iddt = $iddt;

        return $this;
    }
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
