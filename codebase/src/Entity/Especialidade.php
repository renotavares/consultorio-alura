<?php

namespace App\Entity;

use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass : EspecialidadeRepository::class)]
class Especialidade implements \JsonSerializable {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id;

    #[ORM\Column(type: 'string')]
    public $descricao;

    public function getId(): ?int {
        return $this->id;
    }

    public function getDescricao(): ?string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self {
        $this->descricao = $descricao;

        return $this;
    }

    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'descricao' => $this->getDescricao()
        ];
    }
}
