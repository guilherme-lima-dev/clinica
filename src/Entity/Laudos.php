<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Laudos
 *
 * @ORM\Table(name="laudos")
 * @ORM\Entity
 */
class Laudos
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
     * @ORM\Column(name="ds_laudo", type="text", length=65535, nullable=false)
     */
    private $dsLaudo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data", type="datetime", nullable=false)
     */
    private $data;

    /**
     * @ORM\OneToOne(targetEntity="Exames",inversedBy="laudo")
     * @ORM\JoinColumn(name="exame_id",referencedColumnName="id",nullable=true)
    */
    private $exame;

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
    public function getDsLaudo(): ?string
    {
        return $this->dsLaudo;
    }

    /**
     * @param string $dsLaudo
     */
    public function setDsLaudo(string $dsLaudo): void
    {
        $this->dsLaudo = $dsLaudo;
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
     * @return mixed
     */
    public function getExame()
    {
        return $this->exame;
    }

    /**
     * @param mixed $exame
     */
    public function setExame($exame)
    {
        $this->exame = $exame;
        return $this;
    }
}
