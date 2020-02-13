<?php

namespace App\DataTables;

use App\Model\MDocumento;

class DocumentosDenunciasForaneasDataTable extends CustomDataTable
{
    public function setTableId()
    {
        return 'documentos-denuncias-datatable';
    }

    public function setSourceData()
    {
        // Recuperar las direcciones actualmente asignadas al usuario
        $direccionesUsuario = session('DireccionesKeys');

        // Recuperar los departamentos actualmente asignados al usuario
        $departamentosUsuario = session('DepartamentosKeys');
        
        $this->sourceData = MDocumento::with('Detalle','DocumentoForaneo','AcuseRecepcion')
            ->where(function($query) use($direccionesUsuario,$departamentosUsuario){
                $query->whereIn('DOCU_DIRECCION_ORIGEN',$direccionesUsuario);
                $query->orWhereIn('DOCU_DEPARTAMENTO_ORIGEN',$departamentosUsuario);
            })->isForaneo()->siExistente()->noGuardado()->isDocumentoDenuncia()
            ->orderBy('DOCU_CREATED_AT','DESC'); // Documentos de denuncias
    }

    public function columnsTable()
    {
        return [
            [
                'title'  => 'FOLIO RECEPCIÓN',
                'width'  => '18%',
                'render' => function($documento){
                    return $documento->AcuseRecepcion->getNumero();
                }
            ],
            [
                'title'  => 'NÓ. DOCUMENTO',
                'render' => function($documento){
                    return $documento->getNumero();
                }
            ],
            [
                'title'  => 'ASUNTO',
                'render' => function($documento){
                    return $documento->Detalle->getDescripcion();
                }
            ],
            [
                'title'  => 'RECEPCIÓN',
                'class'  => 'text-center',
                'render' => function($documento){
                    return $documento->Detalle->getFechaRecepcion();
                }
            ],
            [
                'title' => 'Tránsito',
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
                'render' => function($documento){
                    if ($documento->DocumentoForaneo->validado() )
                        return $documento->DocumentoForaneo->presenter()->getBadgeValidado();
                    else
                        return $documento->DocumentoForaneo->presenter()->getBadgeEnEspera();
                }
            ],
            [
                'title' => 'Recepcionado',
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
        return url('recepcion/documentos-foraneos/post-data?type=documentos-denuncias');
    }

    public function getCustomOptionsParameters()
    {
        return [
            'pageLength' => 10,
            'order' => [[ 0, 'desc' ]]
        ];
    }

}
