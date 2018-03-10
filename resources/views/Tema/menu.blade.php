<?php
    function activeMenu( $menu_a, $menu_b ){
        return (request()->segment(1) == $menu_a && request()->segment(2) == $menu_b) ? 'class="open"' : '';
    }
    function activeItemMenu( $item ){
        return request()->is($item) ? 'class="active"' : '';
    }
?>
<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">R</span><span class="sidebar-mini-hidden">Recepci&oacute;n</span></li>
        <li {!! activeMenu('recepcion','documentos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-files-o"></i><span class="sidebar-mini-hide">Documentos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('recepcion/documentos/nueva-recepcion') !!} href="{{ url('recepcion/documentos/nueva-recepcion') }}">Nueva recepci&oacute;n</a>
                </li>
                <li>
                    <a {!! activeItemMenu('recepcion/documentos/en-captura') !!} href="{{ url('recepcion/documentos/en-captura') }}">Recepción en captura</a>
                </li>
                <li>
                    <a {!! activeItemMenu('recepcion/documentos/recepcionados') !!} href="{{ url('recepcion/documentos/recepcionados') }}">Recepcionados</a>
                </li>
            </ul>
        </li>
    </ul>
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">P</span><span class="sidebar-mini-hidden">Panel de trabajo</span></li>
        <li {!! activeMenu('panel','documentos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-docs"></i><span class="sidebar-mini-hide">Documentos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('panel/documentos/nuevos') !!} href="{{ url('panel/documentos/nuevos') }}">Nuevos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/todos') !!} href="{{ url('panel/documentos/todos') }}">Todos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/importantes') !!} href="{{ url('panel/documentos/importantes') }}">Importantes</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/archivados') !!} href="{{ url('panel/documentos/archivados') }}">Archivados</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/finalizados') !!} href="{{ url('panel/documentos/finalizados') }}">Finalizados</a>
                </li>
            </ul>
        </li>
        <li {!! activeMenu('panel','reportes') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-file-pdf-o"></i><span class="sidebar-mini-hide">Reportes</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('panel/reportes/generar') !!} href="{{ url('panel/reportes/generar') }}">Generar reporte</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/reportes/configurados') !!} href="{{ url('panel/reportes/configurados') }}">Reportes configurados</a>
                </li>
            </ul>
        </li>
    </ul>
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">C</span><span class="sidebar-mini-hidden">Configuraci&oacute;n</span></li>
        <li {!! activeMenu('configuracion','catalogos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cubes"></i><span class="sidebar-mini-hide">Cat&aacute;logos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/anexos') !!} href="{{ url('configuracion/catalogos/anexos') }}">Anexos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/departamentos') !!} href="{{ url('configuracion/catalogos/departamentos') }}">Departamentos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/direcciones') !!} href="{{ url('configuracion/catalogos/direcciones') }}">Direcciones</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/puestos') !!} href="{{ url('configuracion/catalogos/puestos') }}">Puestos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/estados-documentos') !!} href="{{ url('configuracion/catalogos/estados-documentos') }}">Estados de documentos</a>
                </li>
            </ul>
        </li>
        <li {!! activeMenu('configuracion','usuarios') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-users"></i><span class="sidebar-mini-hidden">Usuarios</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('configuracion/usuarios') !!} href="{{ url('configuracion/usuarios') }}">Ver usuarios</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/usuarios/nuevo') !!} href="{{ url('configuracion/usuarios/nuevo') }}">Nuevo usuario</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/usuarios/roles') !!} href="{{ url('configuracion/usuarios/roles') }}">Roles</a>
                </li>
            </ul>
        </li>
        <li {!! activeMenu('configuracion','sistema') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-cogs"></i><span class="sidebar-mini-hidden">Sistema</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('configuracion/sistema/tipos-documentos') !!} href="{{ url('configuracion/sistema/tipos-documentos') }}">Tipos de documentos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/sistema/estados-documentos') !!} href="{{ url('configuracion/sistema/estados-documentos') }}">Estados de documentos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/sistema/variables') !!} href="{{ url('configuracion/sistema/variables') }}">Variables</a>
                </li>
                <li>
                    <a {!! activeItemMenu('configuracion/sistema/bitacora') !!} href="{{ url('configuracion/usuarios/bitacora') }}">Bitácora</a>
                </li>
            </ul>
        </li>
    </ul>

</div>