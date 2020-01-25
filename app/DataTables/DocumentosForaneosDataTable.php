<?php

namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosForaneosDataTable extends CustomDataTable
{
    public function setTableId()
    {
        return 'documentos-datatable';
    }
    
    public function setSourceData()
    {
        // Recuperar las direcciones actualmente asignadas al usuario
        $direccionesUsuario = session('DireccionesKeys');

        // Recuperar los departamentos actualmente asignados al usuario
        $departamentosUsuario = session('DepartamentosKeys');

        $this->sourceData = MDocumento::with('TipoDocumento','DocumentoForaneo','AcuseRecepcion')
            ->join('system_tipos_documentos','DOCU_SYSTEM_TIPO_DOCTO','=','SYTD_TIPO_DOCUMENTO')
            ->join('detalles','DOCU_DETALLE','=','DETA_DETALLE')
            ->join('acuses_recepcion','DOCU_DOCUMENTO','=','ACUS_DOCUMENTO')
            ->join('documentos_foraneos','DOCU_DOCUMENTO','=','DOFO_DOCUMENTO_LOCAL')
            ->where(function($query) use($direccionesUsuario,$departamentosUsuario){
                $query->whereIn('DOCU_DIRECCION_ORIGEN',$direccionesUsuario);
                $query->orWhereIn('DOCU_DEPARTAMENTO_ORIGEN',$departamentosUsuario);
            })->isForaneo()->siExistente()->noGuardado()->isDocumentoGeneral()
            ->orderBy('DOCU_DOCUMENTO','DESC');
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'data'   => 'ACUS_NUMERO',
                'width'  => '18%',
            ],
            [
                'title'  => 'TIPO DOCUMENTO',
                'data'   => 'SYTD_NOMBRE',
                'width'  => '12%',
                'render' => function($documento){
                    return $documento->TipoDocumento->presenter()->getBadge();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'data'   => 'DOCU_NUMERO_DOCUMENTO',
                'width'  => '15%',
            ],
            [
                'title'  => 'ASUNTO',
                'data'   => 'DETA_DESCRIPCION',
                'render' => function($documento){
                    return ellipsis($documento->DETA_DESCRIPCION,260);
                }
            ],
            [
                'title' => 'RECEPCIÓN',
                'data'  => 'DETA_FECHA_RECEPCION',
                'class' => 'text-center',
            ],
            [
                'title' => 'Tránsito',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->enviado() && !$documento->DocumentoForaneo->recibido())
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnviado();
                    elseif ($documento->DocumentoForaneo->recibido())
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecibido();
                    elseif (user()->can('REC.DOCUMENTO.FORANEO'))
                        return sprintf('<button type="button" class="btn btn-sm btn-success" onclick="hRecepcionForanea.enviar(%d)" title="Enviar documento"><i class="fa fa-fw fa-car"></i> Enviar documento</button>', $documento->DocumentoForaneo->getKey());
                }
            ],
            [
                'title' => 'Validado',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->validado())
                        return $documento->DocumentoForaneo->presenter()->getBadgeValidado();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnEspera();
                }
            ],
            [
                'title' => 'Recepcionado',
                'config' => 'badges',
                'data'   => false,
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->recepcionado() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeRecepcionado();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnEspera();
                }
            ],
            [
                'title'  => 'Opciones',
                'config' => 'options',
                'data'   => false,
                'render' => function($documento){
                    $url_editar = url( sprintf('recepcion/documentos-foraneos/editar-recepcion?search=%d',$documento->DocumentoForaneo->getKey()) );

                    $buttons = '';

                    $buttons .= sprintf('<a class="btn btn-sm btn-circle btn-alt-success" href="%s" title="Editar recepción"><i class="fa fa-fw fa-pencil"></i></a>', $url_editar);

                    $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.anexos(%d)" title="Ver anexos del documento"><i class="fa fa-fw fa-clipboard"></i></button>', $documento->getKey());

                    $url = url( sprintf('recepcion/acuse/documento/%s',$documento->AcuseRecepcion->getNombre()) );
                    $buttons .= sprintf(' <a class="btn btn-sm btn-circle btn-alt-primary" href="%s" target="_blank" title="Acuse de Recepción"><i class="fa fa-fw fa-file-text"></i></a>', $url);

                    if ( user()->can('REC.ELIMINAR.FORANEO') && $documento->recepcionado() ) {
                        $buttons .= sprintf(' <button type="button" class="btn btn-sm btn-circle btn-alt-danger" onclick="hRecepcionForanea.delete_(%d)"><i class="fa fa-trash"></i></button>', $documento->DocumentoForaneo->getKey());
                    }

                    return $buttons;
                }
            ]
        ];
    }

    public function getUrlAjax()
    {
        return '/recepcion/documentos-foraneos/post-data?type=documentos';
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}
