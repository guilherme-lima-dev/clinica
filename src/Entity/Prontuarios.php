<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prontuarios
 *
 * @ORM\Table(name="prontuarios", indexes={@ORM\Index(name="fk_prontuarios_laudos1_idx", columns={"laudos_id"}), @ORM\Index(name="fk_prontuarios_pacientes1_idx", columns={"idpacientes"})})
 * @ORM\Entity
 */
class Prontuarios
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ds_prontuario", type="text", length=65535, nullable=false)
     */
    private $dsProntuario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data;

    /**
     * @var Laudos
     *
     * @ORM\ManyToOne(targetEntity="Laudos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="laudos_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $laudos;

    /**
     * @var Pacientes
     *
     * @ORM\ManyToOne(targetEntity="Pacientes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idpacientes", referencedColumnName="id")
     * })
     */
    private $idpacientes;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getDsProntuario(): ?string
    {
        return $this->dsProntuario;
    }

    /**
     * @param string $dsProntuario
     */
    public function setDsProntuario(string $dsProntuario): void
    {
        $this->dsProntuario = $dsProntuario;
    }

    /**
     * @return \DateTime
     */
    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     */
    public function setData(\DateTime $data): void
    {
        $this->data = $data;
    }

    /**
     * @return Laudos
     */
    public function getLaudos()
    {
        return $this->laudos;
    }

    /**
     * @param Laudos $laudos
     */
    public function setLaudos(Laudos $laudos)
    {
        $this->laudos = $laudos;
        return $this;
    }

    /**
     * @return Pacientes
     */
    public function getIdpacientes(): ?Pacientes
    {
        return $this->idpacientes;
    }

    /**
     * @param Pacientes $idpacientes
     */
    public function setIdpacientes(Pacientes $idpacientes): void
    {
        $this->idpacientes = $idpacientes;
    }
}
