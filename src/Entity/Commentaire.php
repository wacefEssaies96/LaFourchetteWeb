<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
//use App\Entity\Evenement;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_idutilisateur", columns={"idU"}), @ORM\Index(name="fkevent", columns={"idevent"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCO", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idco;

    /**
     * @var string
     *
     * @ORM\Column(name="commantaire", type="string", length=250, nullable=false)
     */
    private $commantaire;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nbrlike", type="integer", nullable=true)
     */
    private $nbrlike;



    /**
     * @var Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur", inversedBy="Commentaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU", nullable=false)
     * })
     */
    private $idu;

    /**
     * @var Evenement
     * @ORM\ManyToOne(targetEntity="Evenement",inversedBy="Commentaire")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="idevent", referencedColumnName="idE")
     * })
     */
    private $idevent;

    public function getIdco(): ?int
    {
        return $this->idco;
    }

    public function getCommantaire(): ?string
    {
        return $this->commantaire;
    }

    public function setCommantaire(string $commantaire): self
    {
        $this->commantaire = $commantaire;

        return $this;
    }

    public function getNbrlike(): ?int
    {
        return $this->nbrlike;
    }

    public function setNbrlike(?int $nbrlike): self
    {
        $this->nbrlike = $nbrlike;

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

    public function getIdevent():?Evenement
    {
        return $this->idevent;
    }

    public function setIdevent(?Evenement $idevent): self
    {
        $this->idevent = $idevent;

        return $this;
    }


}
