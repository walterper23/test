<?php
namespace App\Componentes\Modal;

class ModalBuilder {

	private $modal;
	protected $template = 'form.modal';

	protected $configModal = [
		'id'             => 'modal',
		'modalClass'     => 'modal fade',
		'size'           => 'modal-md',
		'backdrop'       => 'static',
		'keyboard'       => true,
		'header'         => true,
		'headerClass'    => 'bg-primary',
		'footer'         => true,
		'footerClass'    => '',
		'btnOk'          => true,
		'btnOkClass'     => 'btn btn-primary',
		'btnCancel'      => true,
		'btnCancelClass' => 'btn btn-default',

		'title'         => '',
		'content'       => '',
		'btnOkId'       => 'modal-btn-ok',
		'btnCancelId'   => 'modal-btn-cancel',
		'btnOkText'     => 'Aceptar',
		'btnCancelText' => 'Cancelar',
	];

	protected $configButton = [
		'type'		=> 'button',
		'class'		=> 'btn btn-primary',
		'title'     => 'Modal',
		'icon'      => '',
		'addClass'  => ''
	];

	public function __construct(){

	}

	public function getId(){
		return $this->configModal['id'];
	}

	public function setTemplate( $template ){
		$this->template = $template;
		return $this;
	}

	public function getTemplate(){
		return $this->template;
	}

	public function setConfigModal(Array $newConfig){
		$this->configModal = array_merge( $this->configModal, $newConfig );
		return $this;
	}

	public function getConfigModal(){
		return $this->configModal;
	}

	public function setConfigModalParam( $key, $value ){
		$this->configModal[ $key ] = $value;
		return $this;
	}

	/** Configuración del botón para abrir el modal */
	public function setConfigButton(Array $newConfig){
		$this->configButton = array_merge($this->configButton, $newConfig);
		return $this;
	}

	public function getConfigButton(){
		return $this->configButton;
	}

	public function setConfigButtonParam( $key, $value ){
		$this->configButton[ $key ] = $value;
		return $this;
	}

	public function getButton(){
		
		$target   = $this->configModal['id'];

		$backdrop = '';
		if( !empty($this->configModal['backdrop']) ){
			$backdrop = sprintf(' data-backdrop="%s"', $this->configModal['backdrop']);
		}

		$keyboard = '';
		if( !empty($this->configModal['keyboard']) ){
			$keyboard = sprintf(' data-keyboard="%s"', $this->configModal['keyboard']);
		}
		
		$type   = $this->configButton['type'];
		$class  = trim(sprintf('%s %s',$this->configButton['class'],$this->configButton['addClass']));
		$title  = $this->configButton['title'];
		
		$icon = '';
		if( !empty($this->configButton['icon']) ){
			$icon = sprintf('<i class="%s"></i> ', $this->configButton['icon']);
		}

		return sprintf('<%s class="%s" data-toggle="modal" data-target="#%s"%s%s>%s%s</%s>',$type,$class,$target,$backdrop,$keyboard,$icon,$title,$type);
	}
	/**************************************************/

	public function make(){
		$this->modal = view( $this->template )->with( $this->configModal );		
		return $this;
	}

	public function getModal(){
		return $this->modal;
	}

}