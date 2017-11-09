<?php
    function activeItem( $item ){
        return (request()->segment(3)) == $item ? ' active' : '';
    }
?>

<ul class="nav nav-pills">
    <li class="nav-item">
        <a class="nav-link{{ activeItem('anexos') }}" href="{{ url('configuracion/catalogos/anexos') }}">
            <i class="fa fa-fw fa-clipboard mr-5"></i> Anexos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ activeItem('departamentos') }}" href="{{ url('configuracion/catalogos/departamentos') }}">
            <i class="fa fa-fw fa-sitemap mr-5"></i> Departamentos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ activeItem('direcciones') }}" href="{{ url('configuracion/catalogos/direcciones') }}">
            <i class="fa fa-fw fa-sitemap mr-5"></i> Direcciones
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ activeItem('puestos') }}" href="{{ url('configuracion/catalogos/puestos') }}">
            <i class="fa fa-fw fa-users mr-5"></i> Puestos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{ activeItem('tipos-documentos') }}" href="{{ url('configuracion/catalogos/tipos-documentos') }}">
            <i class="fa fa-fw fa-files-o mr-5"></i> Tipos de documentos
        </a>
    </li>
</ul>