<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\OneToMany(targetEntity="Commentaire",mappedBy="Evenement")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ide;

    /**
     * @var string
     *
     * @ORM\Column(name="designationE", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le Champ Designation est obligatoire")
     */
    private $designatione;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionE", type="string", length=500, nullable=false)
     * @Assert\NotBlank(message="Le Champ Description est obligatoire")
     * @Assert\Length(
     *     min=5,
     *     minMessage="La Description doit contenir au moins 5 carcatères "
     * )
     */
    private $descriptione;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateE", type="date", nullable=false)
     *@Assert\Date
     * @Assert\GreaterThanOrEqual("today",message="La date du fin doit être supérieure à la date du jour")

     */
    private $datee;

    /**
     * @var string
     *
     * @ORM\Column(name="imageE", type="string", length=255, nullable=false)
     *  @Assert\NotBlank(message="Le Champ  imaage est obligatoire")
     */
    private $imagee;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrParticipants", type="integer", nullable=false)
     * @Assert\NotBlank(message="Le Champ nbr Participant est obligatoire")
     * @Assert\GreaterThan(
     *     value="1"
     *    , message="Le Nombre de Participants doît être >1")php
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

    public function getDatee()
    {
        return $this->datee;
    }

    public function setDatee($datee): self
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
