<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use InvalidArgumentException;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="App\Repository\StateRepository")
 */
class State
{
    public const STATE_CREATING = 'En création';
    public const STATE_OPENED = 'Ouvert';
    public const STATE_CLOSED = 'Clôturé';
    public const STATE_PENDING = 'En cours';
    public const STATE_FINISHED = 'Terminé';
    public const STATE_CANCELLED = 'Annulé';
    public const STATE_HISTORISED = 'Historisée';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $label;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        if (!in_array($label, array(
            self::STATE_CREATING,
            self::STATE_OPENED,
            self::STATE_CLOSED,
            self::STATE_PENDING,
            self::STATE_FINISHED,
            self::STATE_CANCELLED,
            self::STATE_HISTORISED
        ))) {
            throw new InvalidArgumentException("Invalid state");
        }

        $this->label = $label;

        return $this;
    }
}