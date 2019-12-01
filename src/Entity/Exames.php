<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Exames
 *
 * @ORM\Table(name="exames", indexes={@ORM\Index(name="fk_exames_funcionarios1_idx", columns={"funcionarios_id"}), @ORM\Index(name="fk_exames_pacientes1_idx", columns={"idpacientes"})})
 * @ORM\Entity
 */
class Exames
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
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data;

    /**
     * @var string
     *
     * @ORM\Column(name="imagens", type="string", length=255, nullable=false)
     */
    private $imagens;

    /**
     * @var Funcionarios
     *
     * @ORM\ManyToOne(targetEntity="Funcionarios")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="funcionarios_id", referencedColumnName="id")
     * })
     */
    private $funcionarios;

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
     * @ORM\OneToOne(targetEntity="Laudos",mappedBy="exame")
    */
    private $laudo;

    public function __toString()
    {
        return $this->idpacientes->getNome();
    }

    /**
     * @return int
     */
    public function getId(): ?int
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
     * @return string
     */
    public function getImagens(): ?string
    {
        return $this->imagens;
    }

    /**
     * @param string $imagens
     */
    public function setImagens(string $imagens): void
    {
        $this->imagens = $imagens;
    }

    /**
     * @return Funcionarios
     */
    public function getFuncionarios(): ?Funcionarios
    {
        return $this->funcionarios;
    }

    /**
     * @param Funcionarios $funcionarios
     */
    public function setFuncionarios(Funcionarios $funcionarios): void
    {
        $this->funcionarios = $funcionarios;
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

    /**
     * @return mixed
     */
    public function getLaudo()
    {
        return $this->laudo;
    }

    /**
     * @param mixed $laudo
     */
    public function setLaudo($laudo)
    {
        $this->laudo = $laudo;
        return $this;
    }
}
