@php
    function activeMenu( $menu_a, $menu_b )
    {
        return (request() -> segment(1) == $menu_a && request() -> segment(2) == $menu_b) ? 'class="open"' : '';
    }
    
    function activeItemMenu( $item )
    {
        return request() -> is($item) ? 'class="active"' : '';
    }
@endphp
<div class="content-side content-side-full">
    @can('REC.DOCUMENTO')
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
    @endcan
    <ul class="nav-main">
        <li class="nav-main-heading"><span class="sidebar-mini-visible">P</span><span class="sidebar-mini-hidden">Panel de trabajo</span></li>
        <li {!! activeMenu('panel','documentos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-file-pdf-o"></i><span class="sidebar-mini-hide">Documentos</span></a>
            <ul>
                <li>
                    <a {!! activeItemMenu('panel/documentos/?view=recents') !!} href="{{ url('panel/documentos/?view=recents') }}">Recientes</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/?view=all') !!} href="{{ url('panel/documentos/?view=all') }}">Todos</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/?view=important') !!} href="{{ url('panel/documentos/?view=important') }}">Importantes</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/?view=archived') !!} href="{{ url('panel/documentos/?view=archived') }}">Archivados</a>
                </li>
                <li>
                    <a {!! activeItemMenu('panel/documentos/?view=finished') !!} href="{{ url('panel/documentos/?view=finished') }}">Finalizados</a>
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
        @if( user() -> canAtLeast('SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS','SIS.ADMIN.ESTA.DOC') )
        <li {!! activeMenu('configuracion','catalogos') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-fw fa-cubes"></i><span class="sidebar-mini-hide">Cat&aacute;logos</span></a>
            <ul>
                @can('SIS.ADMIN.ANEXOS')
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/anexos') !!} href="{{ url('configuracion/catalogos/anexos') }}">Anexos</a>
                </li>
                @endcan
                @can('SIS.ADMIN.DIRECC')
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/direcciones') !!} href="{{ url('configuracion/catalogos/direcciones') }}">Direcciones</a>
                </li>
                @endcan
                @can('SIS.ADMIN.DEPTOS')
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/departamentos') !!} href="{{ url('configuracion/catalogos/departamentos') }}">Departamentos</a>
                </li>
                @endcan
                @can('SIS.ADMIN.PUESTOS')
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/puestos') !!} href="{{ url('configuracion/catalogos/puestos') }}">Puestos</a>
                </li>
                @endcan
                @can('SIS.ADMIN.ESTA.DOC')
                <li>
                    <a {!! activeItemMenu('configuracion/catalogos/estados-documentos') !!} href="{{ url('configuracion/catalogos/estados-documentos') }}">Estados de documentos</a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @if( user() -> canAtLeast('USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS') )
        <li {!! activeMenu('configuracion','usuarios') !!}>
            <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-users"></i><span class="sidebar-mini-hidden">Usuarios</span></a>
            <ul>
                @can('USU.ADMIN.USUARIOS')
                <li>
                    <a {!! activeItemMenu('configuracion/usuarios') !!} href="{{ url('configuracion/usuarios') }}">Ver usuarios</a>
                </li>
                @endcan
                @can('USU.ADMIN.PERMISOS')
                <li>
                    <a {!! activeItemMenu('configuracion/usuarios/permisos') !!} href="{{ url('configuracion/usuarios/permisos') }}">Permisos</a>
                </li>
                @endcan
            </ul>
        </li>
        @endif
        @can('SIS.ADMIN.CONFIG')
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
                    <a {!! activeItemMenu('configuracion/sistema/bitacora') !!} href="{{ url('configuracion/sistema/bitacora') }}">Bitácora</a>
                </li>
            </ul>
        </li>
        @endcan
    </ul>

</div>