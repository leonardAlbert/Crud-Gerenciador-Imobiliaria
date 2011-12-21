<?php
/**
 * Administracao_Form_Imobiliaria
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Administracao_Form_Imobiliaria extends Zend_Form
{
	/**
	 * Inicia os dados do formulário
	 *
	 * @name	init
	 * @access	public
	 * @return 	void
	 */
	public function init()
	{
		$this->setName('imobiliarias');
			
		$this->imobiliaria();
	}

	//--------------------------------------------------------------------------

	public function imobiliaria()
	{
		$this->addElements(
		array(
		$this->_getId(),
		$this->_getIdTelefone(),
		$this->_getIdEndereco(),
		$this->_getEnderecoIdImobiliaria(),

		$this->_getNome(),
		$this->_getRazaoSocial(),
		$this->_getTipoTelefone(),
		$this->_getTelefone(),

		$this->_getCnpj(),
		$this->_getSite(),
		$this->_getRepresentante(),

		$this->_getTipoEndereco(),
		$this->_getLogradouro(),
		$this->_getNumeroEnd(),
		$this->_getBairro(),
		$this->_getCep(),
		$this->_getZona(),
		$this->_getCidade(),
		$this->_getEstado(),

		$this->_getSituacao(),
		$this->_getSubmit()
		));
	}

	//--------------------------------------------------------------------------

	private function _getId()
	{
		$id = new Zend_Form_Element_Hidden('id_imobiliaria');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getNome()
	{
		$nome = new Zend_Form_Element_Text('nome');
		$nome->setLabel('Nome Fantasia')
		->setAttrib('placeholder', 'Nome Fantasia')
		->setRequired(true)
		->setAllowEmpty(false)
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_StringLength( array(5, 145) ))
		->addValidator( new Zend_Validate_NotEmpty() )
		->setAttrib('size', '45');

		return $nome;
	}

	//--------------------------------------------------------------------------

	private function _getRazaoSocial()
	{
		$razaoSocial = new Zend_Form_Element_Text('razao_social');
		$razaoSocial->setLabel('Razão Social')
		->setAttrib('placeholder', 'Razão Social')
		->setRequired(true)
		->setAllowEmpty(false)
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_StringLength( array(5, 80) ))
		->addValidator( new Zend_Validate_NotEmpty() )
		->addValidator( new Zend_Validate_StringLength() )
		->setAttrib('size', 45);

		return $razaoSocial;
	}

	//--------------------------------------------------------------------------

	private function _getSite()
	{
		$site = new Zend_Form_Element_Text('site');
		$site->setLabel('Site')
		->setAttrib('placeholder', 'Site');
			
		return $site;
	}

	//--------------------------------------------------------------------------

	private function _getRepresentante()
	{
		$representante = new Zend_Form_Element_Text('representante');
		$representante->setLabel('Representante')
		->setAttrib('placeholder', 'Representante');
			
		return $representante;
	}

	//--------------------------------------------------------------------------

	private function _getCnpj()
	{
		$cnpj = new Zend_Form_Element_Text('cnpj');
		$cnpj->setLabel('CNPJ')
		->setAttrib('placeholder', 'Cnpj')
		->setRequired(true)
		->setAllowEmpty(false)
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_NotEmpty() )
		->addValidator( new Zend_Validate_StringLength(14) );
			
		return $cnpj;
	}

	//--------------------------------------------------------------------------

	private function _getEnderecoIdImobiliaria()
	{
		$id = new Zend_Form_Element_Hidden('endereco_id');

		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getSituacao()
	{
		$situacao = new Zend_Form_Element_Hidden('situacao');
		$situacao->setValue(Application_Model_Usuarios::SITUACAO_ATIVO);

		return $situacao;
	}

	//--------------------------------------------------------------------------


	//--------------------------------------------------------------------------
	// Form Endereco - Form Endereco - Form Endereco - Form Endereco
	//--------------------------------------------------------------------------
	/*
	$this->_getIdEndereco(),
	$this->_getLogradouro(),
	$this->_getNumeroEnd(),
	$this->_getZona(),
	$this->_getBairro(),
	$this->_getCep(),
	$this->_getCidade(),
	$this->_getEstado(),
	$this->_getTipoEndereco(),
	*/

	//--------------------------------------------------------------------------

	private function _getIdEndereco()
	{
		$id = new Zend_Form_Element_Hidden('id_endereco');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getLogradouro()
	{
		$logradouro = new Zend_Form_Element_Text('logradouro');
		$logradouro->setLabel('Endereço')
		->setAttrib('placeholder', 'Endereço')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '25');

		return $logradouro;
	}

	//--------------------------------------------------------------------------

	private function _getNumeroEnd()
	{
		$numero = new Zend_Form_Element_Text('numero_end');
		$numero->setLabel('Numero')
		->setAttrib('placeholder', 'N°')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '10');

		return $numero;
	}

	//--------------------------------------------------------------------------

	private function _getZona()
	{
		$zona = new Zend_Form_Element_Select('zona');
		$zona->setLabel('Zona')
		->setRequired(TRUE)
		->setAllowEmpty(FALSE)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Endereco::ZONA_LESTE, 'Leste')
		->addMultiOption(Application_Model_Endereco::ZONA_NORTE, 'Norte')
		->addMultiOption(Application_Model_Endereco::ZONA_OESTE, 'Oeste')
		->addMultiOption(Application_Model_Endereco::ZONA_SUL, 'Sul');

		return $zona;
	}

	//--------------------------------------------------------------------------

	private function _getBairro()
	{
		$bairro = new Zend_Form_Element_Text('bairro');
		$bairro->setLabel('Bairro')
		->setAttrib('placeholder', 'Bairro')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '20');

		return $bairro;
	}

	//--------------------------------------------------------------------------

	private function _getCep()
	{
		$cep = new Zend_Form_Element_Text('cep');
		$cep->setLabel('CEP')
		->setAttrib('placeholder', 'Cep')
		->setAttrib('size', '10');

		return $cep;
	}

	//--------------------------------------------------------------------------

	private function _getCidade()
	{
		$cidade = new Zend_Form_Element_Text('cidade');
		$cidade->setLabel('Cidade')
		->setAttrib('placeholder', 'Cidade')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '25');

		return $cidade;
	}

	//--------------------------------------------------------------------------

	private function _getEstado()
	{
		$estado = new Zend_Form_Element_Text('estado');
		$estado->setLabel('Estado')
		->setAttrib('placeholder', 'Estado')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '5');

		return $estado;
	}

	//--------------------------------------------------------------------------

	private function _getTipoEndereco()
	{
		$endereco = new Zend_Form_Element_Select('tipo_endereco');
		$endereco->setLabel('Tipo Endereco')
		->setRequired(TRUE)
		->setAllowEmpty(FALSE)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Endereco::ENDERECO_COMERCIAL, 'Comercial')
		->addMultiOption(Application_Model_Endereco::ENDERECO_INDUSTRIAL, 'Industrial')
		->addMultiOption(Application_Model_Endereco::ENDERECO_RESIDENCIAL, 'Residencial');

		return $endereco;
	}

	//--------------------------------------------------------------------------
	// Form Endereco - Form Endereco - Form Endereco - Form Endereco
	//--------------------------------------------------------------------------


	//--------------------------------------------------------------------------
	// Form Telefone - Form Telefone - Form Telefone - Form Telefone
	//--------------------------------------------------------------------------
	/*
	$this->_getIdTelefone(),
	$this->_getTelefone(),
	$this->_getTipoTelefone(),
	*/

	//--------------------------------------------------------------------------

	private function _getIdTelefone()
	{
		$id = new Zend_Form_Element_Hidden('id_telefone');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getTelefone()
	{
		$telefone = new Zend_Form_Element_Text('telefone');
		$telefone->setLabel("Telefone")
		->setAttrib('size', '20')
		->setAttrib('placeholder', 'Telefone')
		->setRequired(TRUE)
		->setAllowEmpty(FALSE);
			
		return $telefone;
	}

	//--------------------------------------------------------------------------

	private function _getTipoTelefone()
	{
		$tipo_telefone = new Zend_Form_Element_Select('tipo_telefone');
		$tipo_telefone->setLabel("Tipo Telefone")
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Telefone::TELEFONE_CELULAR, 'Celular')
		->addMultiOption(Application_Model_Telefone::TELEFONE_COMERCIAL, 'Comercial')
		->addMultiOption(Application_Model_Telefone::TELEFONE_OUTRO, 'Outro')
		->addMultiOption(Application_Model_Telefone::TELEFONE_RECADO, 'Recado')
		->addMultiOption(Application_Model_Telefone::TELEFONE_RESIDENCIAL, 'Residêncial');
			
		return $tipo_telefone;
	}

	//--------------------------------------------------------------------------
	// Form Telefone - Form Telefone - Form Telefone - Form Telefone
	//--------------------------------------------------------------------------
	

	private function _getSubmit()
	{
		$submit = new Zend_Form_Element_Submit('autenticar');
		$submit->setLabel('Enviar');
			
		return $submit;
	}

	//--------------------------------------------------------------------------

	/**
	 * Formulário de alteração dados da imobiliaria
	 *
	 * @param  Zend_Db_Table_Row $row
	 * @return Zend_Form
	 */
	public function formAlterar(Zend_Db_Table_Row $row)
	{
		$this->setAction("/administracao/imobiliaria/alterar/id/{$row->id_imobiliaria}");

		$this->getElement('razao_social')
		->getValidator('Db_NoRecordExists')
		->setExclude(array(
                 'field' => 'id_imobiliaria',
                 'value' => $row->id_imobiliaria
		)
		);

		$this->populate($row->toArray());

		return $this;
	}

	//--------------------------------------------------------------------------

}