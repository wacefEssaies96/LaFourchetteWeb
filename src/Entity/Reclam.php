<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reclam
 *
 * @ORM\Table(name="reclam", indexes={@ORM\Index(name="fk_idU_Rec", columns={"idU"}), @ORM\Index(name="fk_type_Rec", columns={"idrec"})})
 * @ORM\Entity(repositoryClass="App\Repository\ReclamRepository")
 */
class Reclam
{
    /**
     * @var int
     *
     * @ORM\Column(name="idRec", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idrec;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="etatRec", type="string", length=20, nullable=false)
     */
    private $etatrec;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idU", referencedColumnName="idU")
     * })
     */
    private $idu;

    /**
     * @var \TypeRec
     *
     * @ORM\ManyToOne(targetEntity="TypeRec")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="typeRec", referencedColumnName="typeRec")
     * })
     */
    private $typerec;

    public function getIdrec(): ?int
    {
        return $this->idrec;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtatrec(): ?string
    {
        return $this->etatrec;
    }

    public function setEtatrec(string $etatrec): self
    {
        $this->etatrec = $etatrec;

        return $this;
    }

    public function getIdu()
    {
        return $this->idu;
    }

    public function setIdu(?Utilisateur $idu): self
    {
        $this->idu = $idu;

        return $this;
    }

    public function getTyperec()
    {
        return $this->typerec;
    }

    public function setTyperec(?TypeRec $typerec): self
    {
        $this->typerec = $typerec;

        return $this;
    }


}
