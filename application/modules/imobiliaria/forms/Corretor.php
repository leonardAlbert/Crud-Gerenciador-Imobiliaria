<?php
/**
 * Imobiliaria_Form_Corretor
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_Form_Corretor extends Zend_Form
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
		$this->setName('corretor');
			
		$this->corretor();
	}

	//--------------------------------------------------------------------------

	public function corretor()
	{
		$this->addElements(
		array(
		$this->_getId(),
		$this->_getIdEndereco(),
		$this->_getIdTelefone(),
		$this->_getEnderecoIdCorretor(),

		$this->_getDataCadastro(),
		$this->_getSituacao(),

		$this->_getNome(),
		$this->_getCreci(),
		$this->_getTipoTelefone(),
		$this->_getTelefone(),

		$this->_getTipoEndereco(),
		$this->_getLogradouro(),
		$this->_getNumeroEnd(),
		$this->_getBairro(),
		$this->_getCep(),
		$this->_getZona(),
		$this->_getCidade(),
		$this->_getEstado(),

		$this->_getSubmit()
		)
		);
	}

	//--------------------------------------------------------------------------

	private function _getId()
	{
		$id = new Zend_Form_Element_Hidden('id_corretor');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getDataCadastro()
	{
		$data = new Zend_Form_Element_Hidden('data_cadastro');
		$data->setValue(date('Y-m-d'));
			
		return $data;
	}

	//--------------------------------------------------------------------------

	private function _getNome()
	{
		$nome = new Zend_Form_Element_Text('nome');
		$nome->setLabel('Nome')
		->setAttrib('placeholder', 'Nome')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '25');

		return $nome;
	}

	//--------------------------------------------------------------------------

	private function _getSituacao()
	{
		$situacao = new Zend_Form_Element_Hidden('situacao');
		$situacao->setValue(Application_Model_Corretor::SITUACAO_ATIVO);

		return $situacao;
	}

	//--------------------------------------------------------------------------

	private function _getEnderecoIdCorretor()
	{
		$id = new Zend_Form_Element_Hidden('endereco_id');

		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getCreci()
	{
		$creci = new Zend_Form_Element_Text('creci');
		$creci->setLabel('Creci')
		->setAttrib('placeholder', 'Creci');
			
		return $creci;
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
		$salvar = new Zend_Form_Element_Submit('autenticar');
		$salvar->setLabel('Cadastrar Corretor');

		return $salvar;
	}

	//--------------------------------------------------------------------------

	/**
	 * Formulário de alteração dados do corretor
	 *
	 * @param  Zend_Db_Table_Row $row
	 * @return Zend_Form
	 */
	public function formAlterar(Zend_Db_Table_Row $row)
	{
		$this->setAction("/imobiliaria/corretor/alterar/id/{$row->id_corretor}");

		$situacao = new Zend_Form_Element_Radio('situacao');
		$situacao->setLabel('Situação')
		->setAllowEmpty(false)
		->setRequired(true)
		->addMultiOption(Application_Model_Corretor::SITUACAO_ATIVO, 'Ativo')
		->addMultiOption(Application_Model_Corretor::SITUACAO_INATIVO, 'Inativo');

		$this->addElement($situacao);
			
		$this->populate($row->toArray());

		return $this;
	}

	//--------------------------------------------------------------------------
}
