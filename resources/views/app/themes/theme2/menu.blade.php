<!--
Desktop Navigation, mobile navigation can be found in #sidebar

If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
If your sidebar menu includes headings, they won't be visible in your header navigation by default
If your sidebar menu includes icons and you would like to hide them, you can add the class 'nav-main-header-no-icons'
-->
    <li>
        <a href="{{ url('/') }}" @if(request() -> is('/')) class="active" @endif><i class="si si-home"></i>Inicio</a>
    </li>
    @can('REC.DOCUMENTO.LOCAL')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Recepción</a>
        <ul>
            <li>
                <a href="{{ url('recepcion/documentos/nueva-recepcion') }}">Nueva recepci&oacute;n</a>
            </li>
            <!--li>
                <a href="{{ url('recepcion/documentos/en-captura') }}">En captura</a>
            </li-->
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="{{ url('recepcion/documentos/recepcionados?view=denuncias') }}">Recepcionados</a>
                <ul>
                    <li>
                        <a href="{{ url('recepcion/documentos/recepcionados?view=denuncias') }}">Denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos/recepcionados?view=documentos-denuncias') }}">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos/recepcionados?view=documentos') }}">Documentos</a>
                    </li>
                </ul>
            </li>
            @can('REC.RECIBIR.FORANEO')
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="{{ url('recepcion/documentos/foraneos?view=denuncias') }}">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="{{ url('recepcion/documentos/foraneos?view=denuncias') }}">Denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos/foraneos?view=documentos-denuncias') }}">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos/foraneos?view=documentos') }}">Documentos</a>
                    </li>
                </ul>
            </li>
            @endcan
        </ul>
    </li>
    @endcan
    @if (user() -> canAtLeast('REC.DOCUMENTO.FORANEO','REC.VER.FORANEO') )
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Recepción foránea</a>
        <ul>
            @can('REC.DOCUMENTO.FORANEO')
            <li>
                <a href="{{ url('recepcion/documentos-foraneos/nueva-recepcion') }}">Nueva recepci&oacute;n</a>
            </li>
            @endcan
            <!--li>
                <a href="{{ url('recepcion/documentos-foraneos/en-captura') }}">En captura</a>
            </li-->
            @if( user()->canAtLeast('REC.DOCUMENTO.FORANEO','REC.VER.FORANEO') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="{{ url('recepcion/documentos-foraneos/recepcionados') }}">Recepcionados</a>
                <ul>
                    <li>
                        <a href="{{ url('recepcion/documentos-foraneos/recepcionados?view=denuncias') }}">Denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos-foraneos/recepcionados?view=documentos-denuncias') }}">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="{{ url('recepcion/documentos-foraneos/recepcionados?view=documentos') }}">Documentos</a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </li>
    @endcan
    @can('SEG.PANEL.TRABAJO')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-server"></i>Panel de trabajo</a>
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
                <a href="{{ url('panel/documentos/?view=rejected') }}">Rechazados</a>
            </li>
            <li>
                <a href="{{ url('panel/documentos/?view=finished') }}">Finalizados</a>
            </li>
            @can('SEG.ADMIN.SEMAFORO')
            <li>
                <a href="{{ url('panel/documentos/semaforizados') }}">Semaforizados</a>
            </li>
            @endcan
        </ul>
    </li>
    @endcan
    {{--
    @can('REPO.GENERAR.REPORTE')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-file-pdf-o"></i>Reportes</a>
        <ul>
            <li>
                <a href="{{ url('panel/reportes/generar') }}">Generar reporte</a>
            </li>
            <li>
                <a href="{{ url('panel/reportes/configurados') }}">Reportes configurados</a>
            </li>
        </ul>
    </li>
    @endcan
    --}}
    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.CONFIG','SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS',
                    'SIS.ADMIN.ESTA.DOC','USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS.ASIG') )
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-cogs"></i>Configuración</a>
        <ul>
            @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS','SIS.ADMIN.ESTA.DOC') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)">Catálogos</a>
                <ul>
                    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ANEXOS') )
                    <li>
                        <a href="{{ url('configuracion/catalogos/anexos') }}">Anexos</a>
                    </li>
                    @endif
                    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DIRECC') )
                    <li>
                        <a href="{{ url('configuracion/catalogos/direcciones') }}">Direcciones</a>
                    </li>
                    @endif
                    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DEPTOS') )
                    <li>
                        <a href="{{ url('configuracion/catalogos/departamentos') }}">Departamentos</a>
                    </li>
                    @endif
                    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.PUESTOS') )
                    <li>
                        <a href="{{ url('configuracion/catalogos/puestos') }}">Puestos</a>
                    </li>
                    @endif
                    @if( user() -> canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ESTA.DOC' ))
                    <li>
                        <a href="{{ url('configuracion/catalogos/estados-documentos') }}">Estados de documentos</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endcan

            @if( user() -> canAtLeast('USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS.ASIG') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)">Usuarios</a>
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
                <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)">Sistema</a>
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
                    {{--<li>
                        <a href="{{ url('configuracion/sistema/bitacora') }}">Bitácora</a>
                    </li>--}}
                </ul>
            </li>
            @endcan
        </ul>
    </li>
    @endif
    <li>
        <a href="{{ url() -> previous() }}"><i class="si si-action-undo"></i>Regresar</a>
    </li>