<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lieu
 *
 * @ORM\Table(name="LIEUX", indexes={@ORM\Index(name="lieux_villes_fk", columns={"villes_no_ville"})})
 * @ORM\Entity
 */
class Lieu
{
    /**
     * @var int
     *
     * @ORM\Column(name="no_lieu", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noLieu;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_lieu", type="string", length=30, nullable=false)
     */
    private $nomLieu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rue", type="string", length=30, nullable=true)
     */
    private $rue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var \Villes
     *
     * @ORM\ManyToOne(targetEntity="Ville")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="villes_no_ville", referencedColumnName="no_ville")
     * })
     */
    // TODO: Rename relation
    private $villesNoVille;

    /**
     * @return int
     */
    public function getNoLieu()
    {
        return $this->noLieu;
    }

    /**
     * @return float|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string
     */
    public function getNomLieu()
    {
        return $this->nomLieu;
    }

    /**
     * @param string $nomLieu
     */
    public function setNomLieu($nomLieu)
    {
        $this->nomLieu = $nomLieu;
    }

    /**
     * @return string|null
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param string|null $rue
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
    }


}
