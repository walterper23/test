<?php
namespace App\Componentes\Field;

use Form;

class FieldBuilder {

    private $type;
    private $name;
    private $attributes;
    private $options;

    // Nombres de las clases por default para los componentes Label y Control de formulario
    protected $defaultClass = [
        'labelClass'   => 'col-md-4 col-form-label',      // <label class="">
        'controlWidth' => 'col-md-8',      // <div class="">
        'controlClass' => 'form-control',  // <input class="">
    ];

    // Ruta de la carpeta donde se encuentran las plantillas de los distintos campos de formularios
    protected $folderTemplates = 'vendor.form.fields';
    
    // Método que se llama en primer lugar al llamar a algún método estático de esta clase Field
    public function __call($type, $params){
        array_unshift($params,$type); // Agregamos el nombre del input solicitado al array de parámetros
        return call_user_func_array([$this,'input'],$params);
    }

    private function input($type, $name, $value = null, $attributes = [], $options = []){

        $this->type       = $type; // Tipo de control de formulario
        $this->name       = $name; // Atributo name="" del control
        $this->value      = $value; // Atributo value="" del control
        $this->attributes = $attributes; // Atributos extras para el control
        $this->options    = $options; // Array de opciones, en caso de un control Select

        return $this->makeField();

    }

    private function makeField(){

        $this->buildAttributes();
        
        $config['_label'] = $this->_buildLabel();

        if( $config['_label'] ) // Si se debe crear el label
            $config['label'] = $this->buildLabel();

        $config['widthClass'] = $this->defaultClass['controlWidth'];

        $config['control'] = $this->buildControl();

        $template = $this->getTemplate();
        
        return view($template)->with($config);
    }

    private function buildAttributes(){
        
        if( !$this->_getId() ) // Si no se debe incluir el atributo ID al control...
            unset($this->attributes['id']); // ... lo removemos de los atributos
        
        if( in_array($this->type,['select','selectTwo']) ){
            if( !isset($this->attributes['placeholder']) )
                $this->attributes['placeholder'] = 'Seleccione una opción';
            elseif( is_bool($this->attributes['placeholder']) && $this->attributes['placeholder'] == false )
                unset($this->attributes['placeholder']); // Removemos el atributo Placeholder de los atributos
        }
        
        if( isset($this->attributes['labelClass']) ){
            $this->defaultClass['labelClass'] = $this->attributes['labelClass'];
            unset($this->attributes['labelClass']);
        }

        if( isset($this->attributes['addLabelClass']) ){
            $this->defaultClass['labelClass'] .= ' ' . $this->attributes['addLabelClass'];
            unset($this->attributes['addLabelClass']);
        }

        if( !isset($this->attributes['class']) )
            $this->attributes['class'] = $this->defaultClass['controlClass'];

        if( isset($this->attributes['addClass']) ){
            $this->attributes['class'] .= ' ' . $this->attributes['addClass'];
            unset($this->attributes['addClass']); // Removemos el atributo addClass de los atributos
        }

        if( isset($this->attributes['width']) ){
            $this->defaultClass['controlWidth'] = $this->attributes['width'];
            unset($this->attributes['width']); // Removemos el atributo width de los atributos
        }

    }

    // Método para saber si se debe crear el atributo id=""
    private function _getId(){
        if( isset($this->attributes['id']) ){
            if( is_string(($this->attributes['id'])) )
                return true;
            else if( is_bool($this->attributes['id']) && $this->attributes['id'] == false )
                return false;
            else{
                $this->attributes['id'] = '';
                return true;
            }
        }

        $this->attributes['id'] = $this->name;
        return true;
    }

    // Método para retornar el valor del atributo id=""
    private function getId(){
        if( $this->_getId() )
            return $this->attributes['id'];
        return '';
    }

    // Método para saber si se debe crear el Label
    private function _buildLabel(){
        if( isset($this->attributes['label']) ){
            $label = $this->attributes['label'];
            if( is_string($label) || is_numeric($label) )
                return true;
            else if( is_bool($label) && $label == false )
                return false;
            else{
                $this->attributes['label'] = '';
                return true;
            }
        }

        return false;
    }

    // Método para retornar el Label
    private function buildLabel(){
        if( $this->_buildLabel() ){ // Saber si se debe crear el Label
            
            $for   = $this->_getId() ? $this->getId() : $this->name; // Valor del atributo for=""
            $label = $this->attributes['label']; // Valor del Label

            unset($this->attributes['label']); // Removemos el atributo label
            
            return Form::label($for, $label, ['class' => $this->defaultClass['labelClass']]);
        }

        return '';
    }

    private function buildControl(){
        switch( $this->type ){
            case 'text':
            case 'number':
                return Form::text($this->name, $this->value, $this->attributes);
            case 'email':
                return Form::email($this->name, $this->value, $this->attributes);
            case 'select':
                return Form::select($this->name, $this->options, $this->value, $this->attributes);
            case 'selectTwo':
                $this->attributes['class'] .= ' select2 selectTwo';
                $this->attributes['style'] = 'width:100%;';
                $this->attributes['lang']  = 'es';
                return Form::select($this->name, $this->options, $this->value, $this->attributes);
            case 'password':
                return Form::password($this->name, $this->attributes);
            case 'checkbox':
                $valor = is_numeric($this->value) ? ($this->value == 1) : (strtolower($this->value) == 'si'); 
                return Form::checkbox($this->name, $this->value, $valor);
            case 'textarea':
                return Form::textarea($this->name, $this->value, $this->attributes);
            case 'date':
                $this->attributes['class'] .= ' datepicker';
                return Form::text($this->name, $this->value, $this->attributes);
            case 'hidden':
                return Form::hidden($this->name, $this->value, $this->attributes);
            default:
                return Form::input($this->type, $this->name, $this->value, $this->attributes);
        }
    }

    // Método para recuperar la plantilla de diseño para el control solicitado
    private function getTemplate(){

        // Habilitar en caso de más plantillas de diseño para los controles
        switch ( $this->type ) {
            case 'hidden':
                $template = 'hidden';
                break;         
            default:
                $template = 'default';
                break;
        }

        return sprintf('%s.%s',$this->folderTemplates,$template);

    }

} 