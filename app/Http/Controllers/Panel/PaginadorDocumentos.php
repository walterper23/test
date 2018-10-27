<?php
namespace App\Panel;

use Illuminate\Support\Collection;

class PaginadorDocumentos
{
    public $pagina_actual;
    public $total_paginas;
    public $total_documentos;
    public $pagina_inicio;
    public $pagina_fin;

    public $pagina_anterior;
    public $pagina_siguiente;

    public $parametros;

    public function __construct(Collection $documentos, $intervalo = 10, $pagina = 1)
    {
        if( $intervalo < 1 )
        {
            $intervalo = 10;
        }

        if( $pagina < 1 )
        {
            $pagina = 1;
        }

        $this->documentos = $documentos;

        $this->intervalo = $intervalo;
        $this->pagina    = $pagina;

        $this->pagina_actual    = 0;
        $this->total_paginas    = 0;
        $this->total_documentos = 0;
        $this->pagina_inicio    = 0;
        $this->pagina_fin       = 0;

        $this->pagina_anterior  = false;
        $this->pagina_siguiente = false;

        $this->url = url()->current() . '?';
        $this->parametros = [];

        $this->pagina_anterior_url  = '';
        $this->pagina_siguiente_url = '';
        
        $this->procesarDocumentos();
    }

    private function procesarDocumentos()
    {
        $this->total_documentos = $this->documentos->count();

        $documentos_paginados = $this->documentos->chunk($this->intervalo);

        $this->total_paginas = $documentos_paginados->count();

        if( $this->total_paginas > 0 )
        {


            if( $this->pagina > $this->total_paginas )
            {
                $this->pagina = 1;
            }

            $this->documentos = $documentos_paginados->get($this->pagina - 1);

            if( $this->pagina > 1 )
            {
                $this->pagina_anterior = true;
            }

            // dd($this->pagina, $this->total_paginas, $this->pagina_anterior);

            if( $this->pagina < $this->total_paginas )
            {
                $this->pagina_siguiente = true;
            }

            $this->pagina_actual = $this->pagina;

            $this->pagina_inicio = $this->documentos->keys()->first() + 1;
            $this->pagina_fin    = $this->documentos->keys()->reverse()->first() + 1;

            $this->procesarEnlaces();
        }

        return $this;
    }

    public function setUrl( $url )
    {
        $this->url = $url;
    }

    public function addParametros( $parametros = [] )
    {
        $this->parametros = $parametros;
        $this->procesarEnlaces();
    }

    private function procesarEnlaces()
    {
        $parametros = $this->parametros;
        $step = $this->intervalo;

        if( $this->pagina_anterior )
        {
            $page = $this->pagina - 1;
            $this->pagina_anterior_url  = $this->url . http_build_query( $parametros + compact('step','page'));
        }

        unset($page);

        if( $this->pagina_siguiente )
        {
            $page = $this->pagina + 1;
            $this->pagina_siguiente_url = $this->url . http_build_query( $parametros + compact('step','page'));
        }
    }

    public function getDocumentos()
    {
        return $this->documentos;
    }


}