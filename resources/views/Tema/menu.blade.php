<?php
    function activeMenu( $menu ){
        return (request()->segment(2) == $menu) ? 'class="open"' : '';
    }
    function activeItemMenu( $item ){
        return (request()->segment(3) == $item) ? 'class="active"' : '';
    }
?>
<div class="content-side content-side-full">
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Recepci&oacute;n</span></li>
        <li {!! activeMenu('documentos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Documentos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('') !!} href="{{ url('/recepcion/documentos/recepcionados') }}">Recepcionados</a>
                </li>
                <li>
                    <a {!! activeItemMenu('nuevo') !!} href="{{ url('/recepcion/documentos/nuevo') }}">Nueva recepci&oacute;n</a>
                </li>
            </ul>
        </li>
    </
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">Configuraci&oacute;n</span></li>
        <li {!! activeMenu('catalogos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">Cat&aacute;logos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('anexos') !!} href="{{ url('/configuracion/catalogos/anexos') }}">Anexos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('departamentos') !!} href="{{ url('/configuracion/catalogos/departamentos') }}">Departamentos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('direcciones') !!} href="{{ url('/configuracion/catalogos/direcciones') }}">Direcciones</a>
                </li>
                <li>
                    <a {!! activeItemMenu('puestos') !!} href="{{ url('/configuracion/catalogos/puestos') }}">Puestos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('tipos-documentos') !!} href="{{ url('/configuracion/catalogos/tipos-documentos') }}">Tipos de documentos</a>
                </li>
            </ul>
        </li>
        <li {!! activeMenu('usuarios') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-users"></i><span class="sidebar-mini-hidden">Usuarios</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('') !!} href="{{ url('/configuracion/usuarios/') }}">Ver usuarios</a>
                </li>
                <li>
                    <a {!! activeItemMenu('nuevo') !!} href="{{ url('/configuracion/usuarios/nuevo') }}">Nuevo usuario</a>
                </li>
                <li>
                    <a {!! activeItemMenu('roles') !!} href="{{ url('/configuracion/usuarios/roles') }}">Roles</a>
                </li>
                <li>
                    <a {!! activeItemMenu('permisos') !!} href="{{ url('/configuracion/usuarios/permisos') }}">Permisos</a>
                </li>
            </ul>
        </li>
    </ul>
</div>