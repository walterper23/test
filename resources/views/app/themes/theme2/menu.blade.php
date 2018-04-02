<!--
Desktop Navigation, mobile navigation can be found in #sidebar

If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
If your sidebar menu includes headings, they won't be visible in your header navigation by default
If your sidebar menu includes icons and you would like to hide them, you can add the class 'nav-main-header-no-icons'
-->
<ul class="nav-main-header">
    @can('REC.DOCUMENTO')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-fw fa-files-o"></i>Recepción</a>
        <ul>
            <li>
                <a href="{{ url('recepcion/documentos/nueva-recepcion') }}">Nueva recepci&oacute;n</a>
            </li>
            <li>
                <a href="{{ url('recepcion/documentos/en-captura') }}">En captura</a>
            </li>
            <li>
                <a href="{{ url('recepcion/documentos/recepcionados') }}">Recepcionados</a>
            </li>
        </ul>
    </li>
    @endcan
    @can('SEG.PANEL.TRABAJO')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-server"></i>Panel de trabajo</a>
        <ul>
            <li>
                <a href="{{ url('panel/documentos/?view=recents') }}">Recientes</a>
            </li>
            <li>
                <a href="{{ url('panel/documentos/?view=all') }}">Todos</a>
            </li>
            <li>
                <a href="{{ url('panel/documentos/?view=important') }}">Importantes</a>
            </li>
            <li>
                <a href="{{ url('panel/documentos/?view=archived') }}">Archivados</a>
            </li>
            <li>
                <a href="{{ url('panel/documentos/?view=finished') }}">Finalizados</a>
            </li>
        </ul>
    </li>
    @endcan
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-file-pdf-o"></i>Reportes</a>
        <ul>
            <li>
                <a href="{{ url('panel/reportes/generar') }}">Generar reporte</a>
            </li>
            <li>
                <a href="{{ url('panel/reportes/configurados') }}">Reportes configurados</a>
            </li>
        </ul>
    </li>
    @if( user() -> canAtLeast('SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS','SIS.ADMIN.ESTA.DOC') )
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-fw fa-cogs"></i>Configuración</a>
        <ul>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Catálogos</a>
                <ul>
                    @can('SIS.ADMIN.ANEXOS')
                    <li>
                        <a href="{{ url('configuracion/catalogos/anexos') }}">Anexos</a>
                    </li>
                    @endcan
                    @can('SIS.ADMIN.DIRECC')
                    <li>
                        <a href="{{ url('configuracion/catalogos/direcciones') }}">Direcciones</a>
                    </li>
                    @endcan
                    @can('SIS.ADMIN.DEPTOS')
                    <li>
                        <a href="{{ url('configuracion/catalogos/departamentos') }}">Departamentos</a>
                    </li>
                    @endcan
                    @can('SIS.ADMIN.PUESTOS')
                    <li>
                        <a href="{{ url('configuracion/catalogos/puestos') }}">Puestos</a>
                    </li>
                    @endcan
                    @can('SIS.ADMIN.ESTA.DOC')
                    <li>
                        <a href="{{ url('configuracion/catalogos/estados-documentos') }}">Estados de documentos</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @if( user() -> canAtLeast('USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS.ASIG') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Usuarios</a>
                <ul>
                    @can('USU.ADMIN.USUARIOS')
                    <li>
                        <a href="{{ url('configuracion/usuarios') }}">Ver usuarios</a>
                    </li>
                    @endcan
                    @can('USU.ADMIN.PERMISOS.ASIG')
                    <li>
                        <a href="{{ url('configuracion/usuarios/permisos-asignaciones') }}">Permisos y Asignaciones</a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endif
            @can('SIS.ADMIN.CONFIG')
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="#">Sistema</a>
                <ul>
                    <li>
                        <a href="{{ url('configuracion/sistema/tipos-documentos') }}">Tipos de documentos</a>
                    </li>
                    <li>
                        <a href="{{ url('configuracion/sistema/estados-documentos') }}">Estados de documentos</a>
                    </li>
                    <li>
                        <a href="{{ url('configuracion/sistema/variables') }}">Variables</a>
                    </li>
                    <li>
                        <a href="{{ url('configuracion/sistema/bitacora') }}">Bitácora</a>
                    </li>
                </ul>
            </li>
            @endcan
        </ul>
    </li>
    @endif
    <li>
        <a href="{{ url() -> previous() }}"><i class="si si-action-undo"></i>Regresar</a>
    </li>
</ul>