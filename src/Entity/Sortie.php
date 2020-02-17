<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sorties
 *
 * @ORM\Table(name="SORTIES", indexes={@ORM\Index(name="sorties_etats_fk", columns={"etats_no_etat"}), @ORM\Index(name="sorties_lieux_fk", columns={"lieux_no_lieu"}), @ORM\Index(name="sorties_participants_fk", columns={"organisateur"})})
 * @ORM\Entity
 */
class Sortie
{
    /**
     * @var int
     *
     * @ORM\Column(name="no_sortie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noSortie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30, nullable=false)
     */
    private $nom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datedebut", type="datetime", nullable=false)
     */
    private $datedebut;

    /**
     * @var int|null
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecloture", type="datetime", nullable=false)
     */
    private $datecloture;

    /**
     * @var int
     *
     * @ORM\Column(name="nbinscriptionsmax", type="integer", nullable=false)
     */
    private $nbinscriptionsmax;

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return \DateTime
     */
    public function getDatedebut()
    {
        return $this->datedebut;
    }

    /**
     * @param \DateTime $datedebut
     */
    public function setDatedebut($datedebut)
    {
        $this->datedebut = $datedebut;
    }

    /**
     * @return int|null
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * @param int|null $duree
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    }

    /**
     * @return \DateTime
     */
    public function getDatecloture()
    {
        return $this->datecloture;
    }

    /**
     * @param \DateTime $datecloture
     */
    public function setDatecloture($datecloture)
    {
        $this->datecloture = $datecloture;
    }

    /**
     * @return int
     */
    public function getNbinscriptionsmax()
    {
        return $this->nbinscriptionsmax;
    }

    /**
     * @param int $nbinscriptionsmax
     */
    public function setNbinscriptionsmax($nbinscriptionsmax)
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;
    }

    /**
     * @return string|null
     */
    public function getDescriptioninfos()
    {
        return $this->descriptioninfos;
    }

    /**
     * @param string|null $descriptioninfos
     */
    public function setDescriptioninfos($descriptioninfos)
    {
        $this->descriptioninfos = $descriptioninfos;
    }

    /**
     * @return int|null
     */
    public function getEtatsortie()
    {
        return $this->etatsortie;
    }

    /**
     * @param int|null $etatsortie
     */
    public function setEtatsortie($etatsortie)
    {
        $this->etatsortie = $etatsortie;
    }

    /**
     * @return string|null
     */
    public function getUrlphoto()
    {
        return $this->urlphoto;
    }

    /**
     * @param string|null $urlphoto
     */
    public function setUrlphoto($urlphoto)
    {
        $this->urlphoto = $urlphoto;
    }

    /**
     * @return \Participants
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param \Participants $organisateur
     */
    public function setOrganisateur($organisateur)
    {
        $this->organisateur = $organisateur;
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="descriptioninfos", type="string", length=500, nullable=true)
     */
    private $descriptioninfos;

    /**
     * @var int|null
     *
     * @ORM\Column(name="etatsortie", type="integer", nullable=true)
     */
    private $etatsortie;

    /**
     * @var string|null
     *
     * @ORM\Column(name="urlPhoto", type="string", length=250, nullable=true)
     */
    private $urlphoto;

    /**
     * @var \Etats
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="etats_no_etat", referencedColumnName="no_etat")
     * })
     */
    // TODO: Rename entity
    private $etatsNoEtat;

    /**
     * @var \Lieux
     *
     * @ORM\ManyToOne(targetEntity="Lieu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="lieux_no_lieu", referencedColumnName="no_lieu")
     * })
     */
    // TODO: Rename entity
    private $lieuxNoLieu;

    /**
     * @var \Participants
     *
     * @ORM\ManyToOne(targetEntity="Participant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="organisateur", referencedColumnName="no_participant")
     * })
     */
    private $organisateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Participant", inversedBy="sortiesNoSortie")
     * @ORM\JoinTable(name="inscriptions",
     *   joinColumns={
     *     @ORM\JoinColumn(name="sorties_no_sortie", referencedColumnName="no_sortie")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="participants_no_participant", referencedColumnName="no_participant")
     *   }
     * )
     */
    // TODO: Rename entity
    private $participantsNoParticipant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->participantsNoParticipant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getNoSortie()
    {
        return $this->noSortie;
    }

}
