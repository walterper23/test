<!--
Desktop Navigation, mobile navigation can be found in #sidebar

If you would like to use the same navigation in both mobiles and desktops, you can use exactly the same markup inside sidebar and header navigation ul lists
If your sidebar menu includes headings, they won't be visible in your header navigation by default
If your sidebar menu includes icons and you would like to hide them, you can add the class 'nav-main-header-no-icons'
-->
    <li>
        <a href="/" @if(request()->is('/')) class="active" @endif><i class="si si-home"></i>Inicio</a>
    </li>

    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Afiliación</a>
        <ul>
            <li>
                <a href="imjuve/afiliacion/">Afiliados</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos/en-captura">En captura</a>
            </li-->
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/recepcionados?view=denuncias">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/foraneos?view=denuncias">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>    
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Eventos</a>
        <ul>
            <li>
                <a href="imjuve/afiliacion/">Listado de eventos</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos/en-captura">En captura</a>
            </li-->
            <li>
                        <a href="/imjuve/actividades">Actividades</a>
                    </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/recepcionados?view=denuncias">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/foraneos?view=denuncias">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Instituciones</a>
        <ul>
            <li>
                <a href="imjuve/afiliacion/">Afiliados</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos/en-captura">En captura</a>
            </li-->
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/recepcionados?view=denuncias">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/foraneos?view=denuncias">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Catalogos</a>
        <ul>
            <li>
                <a href="/afiliacion/">Actividades</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos/en-captura">En captura</a>
            </li-->
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/recepcionados?view=denuncias">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/foraneos?view=denuncias">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Recepción</a>
        <ul>
            <li>
                <a href="/recepcion/documentos/nueva-recepcion">Nueva recepci&oacute;n</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos/en-captura">En captura</a>
            </li-->
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/recepcionados?view=denuncias">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos/foraneos?view=denuncias">Recibir recepciones foráneas</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos/foraneos?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    <!--li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-files-o"></i>Recepción foránea</a>
        <ul>
            <li>
                <a href="/recepcion/documentos-foraneos/nueva-recepcion">Nueva recepci&oacute;n</a>
            </li>
            <!--li>
                <a href="/recepcion/documentos-foraneos/en-captura">En captura</a>
            </li>
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="/recepcion/documentos-foraneos/recepcionados">Recepcionados</a>
                <ul>
                    <li>
                        <a href="/recepcion/documentos-foraneos/recepcionados?view=denuncias">Denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos-foraneos/recepcionados?view=documentos-denuncias">Documentos de denuncias</a>
                    </li>
                    <li>
                        <a href="/recepcion/documentos-foraneos/recepcionados?view=documentos">Documentos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </li-->
    <!--li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-server"></i>Panel de trabajo</a>
        <ul>
            <li>
                <a href="/panel/documentos/?view=pending">Por turnar</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=moved">Turnados</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=all">Todos</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=important">Importantes</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=archived">Archivados</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=rejected">Rechazados</a>
            </li>
            <li>
                <a href="/panel/documentos/?view=finished">Concluidos</a>
            </li>
        </ul>
    </li>
        <li>
            <a href="/panel/documentos/semaforizados"><i class="fa fa-warning"></i>Semaforización</a>
        </li-->
    {{--
    @can('REPO.GENERAR.REPORTE')
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-file-pdf-o"></i>Reportes</a>
        <ul>
            <li>
                <a href="/panel/reportes/generar">Generar reporte</a>
            </li>
            <li>
                <a href="/panel/reportes/configurados">Reportes configurados</a>
            </li>
        </ul>
    </li>
    @endcan
    --}}
    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.CONFIG','SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS',
                    'SIS.ADMIN.ESTA.DOC','USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS.ASIG') )
    <li>
        <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)"><i class="fa fa-fw fa-cogs"></i>Configuración</a>
        <ul>
            @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ANEXOS','SIS.ADMIN.DIRECC','SIS.ADMIN.DEPTOS','SIS.ADMIN.PUESTOS','SIS.ADMIN.ESTA.DOC') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)">Catálogos</a>
                <ul>
                    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ANEXOS') )
                    <li>
                        <a href="/configuracion/catalogos/anexos">Anexos</a>
                    </li>
                    @endif
                    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DIRECC') )
                    <li>
                        <a href="/configuracion/catalogos/direcciones">Direcciones</a>
                    </li>
                    @endif
                    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.DEPTOS') )
                    <li>
                        <a href="/configuracion/catalogos/departamentos">Departamentos</a>
                    </li>
                    @endif
                    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.PUESTOS') )
                    <li>
                        <a href="/configuracion/catalogos/puestos">Puestos</a>
                    </li>
                    @endif
                    @if( user()->canAtLeast('SIS.ADMIN.CATALOGOS','SIS.ADMIN.ESTA.DOC' ))
                    <li>
                        <a href="/configuracion/catalogos/estados-documentos">Estados de documentos</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endcan

            @if( user()->canAtLeast('USU.ADMIN.USUARIOS','USU.ADMIN.PERMISOS.ASIG') )
            <li>
                <a class="nav-submenu" data-toggle="nav-submenu" href="javascript:void(0)">Usuarios</a>
                <ul>
                    @can('USU.ADMIN.USUARIOS')
                    <li>
                        <a href="/configuracion/usuarios">Ver usuarios</a>
                    </li>
                    @endcan
                    @can('USU.ADMIN.PERMISOS.ASIG')
                    <li>
                        <a href="/configuracion/usuarios/permisos-asignaciones">Permisos y Asignaciones</a>
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
                        <a href="/configuracion/sistema/tipos-documentos">Tipos de documentos</a>
                    </li>
                    <li>
                        <a href="/configuracion/sistema/estados-documentos">Estados de documentos</a>
                    </li>
                    <li>
                        <a href="/configuracion/sistema/variables">Variables</a>
                    </li>
                    {{--<li>
                        <a href="/configuracion/sistema/bitacora">Bitácora</a>
                    </li>--}}
                </ul>
            </li>
            @endcan
        </ul>
    </li>
    @endif