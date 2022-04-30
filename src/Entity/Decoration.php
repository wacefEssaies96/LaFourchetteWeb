<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Decoration
 *
 * @ORM\Table(name="decoration")
 * 
 * @ORM\Entity(repositoryClass="App\Repository\DecorationRepository")
 */
class Decoration
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdD", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\OneToMany(targetEntity="DecorationReservation",mappedBy="Decoration")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idd;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le Champ Nom est obligatoire")
     */
    private $nom;

    /**
     * @var float
     *
     * @ORM\Column(name="Prix", type="float", precision=10, scale=0, nullable=false)
     * @Assert\NotBlank(message="Le Champ prix est obligatoire")
     * @Assert\GreaterThan(
     *     value = 10,
     *     message="Le prix doit etre superieur a 10"
     * )
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="ImageD", type="string", length=100, nullable=false)
     * @Assert\NotBlank(message="Le Champ Image est obligatoire")
     */
    private $imaged;

    public function getIdd(): ?int
    {
        return $this->idd;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImaged(): ?string
    {
        return $this->imaged;
    }

    public function setImaged(string $imaged): self
    {
        $this->imaged = $imaged;

        return $this;
    }


}
