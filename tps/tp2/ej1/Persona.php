<?php

namespace Ipoo\Tps\Tp2\Ej1;

use Ipoo\BaseClass;

/**
 * @property string $nombre
 * @property string $apellido
 * @property string $tipoDocumento
 * @property int $numeroDocumento
 */
class Persona extends BaseClass
{
    protected array $attributes = ["nombre", "apellido", "tipoDocumento", "numeroDocumento"];
    //ACCESSORS

    public function getNombre(): string
    {
        return $this->nombre ?? "";
    }

    public function getApellido(): string
    {
        return $this->apellido ?? "";
    }

    public function getTipoDocumento(): string
    {
        return $this->tipoDocumento ?? "";
    }

    public function getNumeroDocumento(): int
    {
        return $this->numeroDocumento ?? "";
    }

    // MUTATORS

    public function setNombre(string $_nombre): void
    {
        $this->nombre = $_nombre;
    }

    public function setApellido(string $_apellido): void
    {
        $this->apellido = $_apellido;
    }

    public function setTipoDocumento(string $_tipoDocumento)
    {
        $this->tipoDocumento = $_tipoDocumento;
    }

    public function setNumeroDocumento(int $_numeroDocumento)
    {
        $this->numeroDocumento = $_numeroDocumento;
    }
}
