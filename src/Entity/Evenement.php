<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="App\Repository\EvenementRepository")
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idE", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ide;

    /**
     * @var string
     *
     * @ORM\Column(name="designationE", type="string", length=255, nullable=false)
     */
    private $designatione;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionE", type="string", length=500, nullable=false)
     */
    private $descriptione;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateE", type="date", nullable=false)
     */
    private $datee;

    /**
     * @var string
     *
     * @ORM\Column(name="imageE", type="string", length=255, nullable=false)
     */
    private $imagee;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrParticipants", type="integer", nullable=false)
     */
    private $nbrparticipants;

    public function getIde(): ?int
    {
        return $this->ide;
    }

    public function getDesignatione(): ?string
    {
        return $this->designatione;
    }

    public function setDesignatione(string $designatione): self
    {
        $this->designatione = $designatione;

        return $this;
    }

    public function getDescriptione(): ?string
    {
        return $this->descriptione;
    }

    public function setDescriptione(string $descriptione): self
    {
        $this->descriptione = $descriptione;

        return $this;
    }

    public function getDatee(): ?\DateTimeInterface
    {
        return $this->datee;
    }

    public function setDatee(\DateTimeInterface $datee): self
    {
        $this->datee = $datee;

        return $this;
    }

    public function getImagee(): ?string
    {
        return $this->imagee;
    }

    public function setImagee(string $imagee): self
    {
        $this->imagee = $imagee;

        return $this;
    }

    public function getNbrparticipants(): ?int
    {
        return $this->nbrparticipants;
    }

    public function setNbrparticipants(int $nbrparticipants): self
    {
        $this->nbrparticipants = $nbrparticipants;

        return $this;
    }


}
