<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sites
 *
 * @ORM\Table(name="SITES")
 * @ORM\Entity
 */
class Site
{
    /**
     * @var int
     *
     * @ORM\Column(name="no_site", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $noSite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_site", type="string", length=30, nullable=false)
     */
    private $nomSite;

    /**
     * @return int
     */
    public function getNoSite()
    {
        return $this->noSite;
    }

    /**
     * @return string
     */
    public function getNomSite()
    {
        return $this->nomSite;
    }

    /**
     * @param string $nomSite
     */
    public function setNomSite($nomSite)
    {
        $this->nomSite = $nomSite;
    }


}
