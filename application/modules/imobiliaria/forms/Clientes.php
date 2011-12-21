<?php
/**
 * Imobiliaria_Form_Clientes
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_Form_Clientes extends Zend_Form
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
		$this->setName('clientes');
			
		$this->clientes();
	}

	//--------------------------------------------------------------------------

	public function clientes()
	{
		$this->addElements(
		array(
		$this->_getId(),
		$this->_getIdTelefone(),
		$this->_getIdEndereco(),
		$this->_getEnderecoIdCliente(),
		$this->_getInteresseId(),

		$this->_getTipoPessoa(),
		$this->_getTipoCadastro(),

		$this->_getNome(),
		$this->_getSexo(),
		$this->_getDataNascimento(),
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


		$this->_getCpf(),
		$this->_getCnpj(),
		$this->_getRg(),
		$this->_getProfissao(),
		$this->_getNacionalidade(),
		$this->_getEstadoCivil(),
		$this->_getEmail(),

		$this->_getAnotacoes(),
		$this->_getDataCadastro(),
		$this->_getUltimoContato(),
			
		$this->_getSituacao(),
		$this->_getSubmit()
		)
		);
	}

	//--------------------------------------------------------------------------

	private function _getId()
	{
		$id = new Zend_Form_Element_Hidden('id_cliente');
			
		return $id;
	}

	//--------------------------------------------------------------------------
	
	private function _getInteresseId()
	{
		$id = new Zend_Form_Element_Hidden('interesse_id');
			
		return $id;
	}

	//--------------------------------------------------------------------------
	private function _getTipoPessoa()
	{
		$tipo = new Zend_Form_Element_Select('tipo_pessoa');
		$tipo->setLabel('Tipo Pessoa')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Clientes::PESSOA_FISICA, 'Pessoa Fisica')
		->addMultiOption(Application_Model_Clientes::PESSOA_JURIDICA, 'Pessoa Juridica');

		return $tipo;
	}

	//--------------------------------------------------------------------------

	private function _getTipoCadastro()
	{
		$tipoCad = new Zend_Form_Element_Select('tipo_cadastro');
		$tipoCad->setLabel('Tipo Cadastro')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Clientes::CADASTRO_COMPRADOR, 'Comprador')
		->addMultiOption(Application_Model_Clientes::CADASTRO_FIADOR, 'Fiador')
		->addMultiOption(Application_Model_Clientes::CADASTRO_LOCADOR, 'Locador')
		->addMultiOption(Application_Model_Clientes::CADASTRO_LOCATARIO, 'Locatario')
		->addMultiOption(Application_Model_Clientes::CADASTRO_PROPRIETARIO, 'Proprietario');

		return $tipoCad;
	}

	//--------------------------------------------------------------------------

	private function _getNome()
	{
		$nome = new Zend_Form_Element_Text('nome');
		$nome->setLabel('Nome')
		->setAttrib('placeholder', 'Nome Completo')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '28');

		return $nome;
	}

	//--------------------------------------------------------------------------
	
	private function _getSexo()
	{
		$sexo = new Zend_Form_Element_Select('sexo');
		$sexo->setLabel('Sexo')
			->setRequired(true)
			->setAllowEmpty(false)
			->addMultiOption("", "Selecione")
			->addMultiOption(Application_Model_Clientes::SEXO_MASCULINO, 'Masculino')
			->addMultiOption(Application_Model_Clientes::SEXO_FEMININO, 'Feminino');

		return $sexo;
	}

	//--------------------------------------------------------------------------

	private function _getCpf()
	{
		$cpf = new Zend_Form_Element_Text('cpf');
		$cpf->setLabel('Cpf')
		->setAttrib('placeholder', 'Cpf')
		->setAttrib('size', '40');

		return $cpf;
	}

	//--------------------------------------------------------------------------

	private function _getCnpj()
	{
		$cnpj = new Zend_Form_Element_Text('cnpj');
		$cnpj->setLabel('Cnpj')
		->setAttrib('placeholder', 'Cnpj')
		->setAttrib('size', '40');

		return $cnpj;
	}

	//--------------------------------------------------------------------------

	private function _getRg()
	{
		$rg = new Zend_Form_Element_Text('rg');
		$rg->setLabel('Rg')
		->setAttrib('placeholder', 'Rg')
		->setAttrib('size', '40');

		return $rg;
	}

	//--------------------------------------------------------------------------

	public function _getNacionalidade()
	{
		$nasc = new Zend_Form_Element_Text('nacionalidade');
		$nasc->setLabel('Nacionalidade')
		->setAttrib('size', '40')
		->setAttrib('placeholder', 'Nacionalidade');

		return $nasc;
	}

	//--------------------------------------------------------------------------

	private function _getAnotacoes()
	{
		$anotacoes = new Zend_Form_Element_Textarea('anotacoes');
		$anotacoes->setLabel('Anotações')
		->setAttrib('placeholder', 'Anotações')
	->setAttrib('COLS', '40')
		->setAttrib('ROWS', '4');

		return $anotacoes;
	}

	//--------------------------------------------------------------------------

	private function _getDataNascimento()
	{
		$dt_nasc = new Zend_Form_Element_Text('data_nascimento');
		$dt_nasc->setLabel('Data Nasc')
		->setAttrib('placeholder', 'Data Nasc')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '10');

		return $dt_nasc;
	}

	//--------------------------------------------------------------------------

	private function _getProfissao()
	{
		$profissao = new Zend_Form_Element_Text('profissao');
		$profissao->setLabel('Profissão')
		->setAttrib('placeholder', 'Profissão')
		->setAttrib('size', '40');

		return $profissao;
	}

	//--------------------------------------------------------------------------

	private function _getEstadoCivil()
	{
		$civil = new Zend_Form_Element_Select('estado_civil');
		$civil->setLabel('Estado Civil')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Clientes::CIVIL_CASADO, 'Casado(a)')
		->addMultiOption(Application_Model_Clientes::CIVIL_DIVORCIADO, 'Divorciado(a)')
		->addMultiOption(Application_Model_Clientes::CIVIL_SEPARADO, 'Separado(a)')
		->addMultiOption(Application_Model_Clientes::CIVIL_SOLTEIRO, 'Solteiro(a)')
		->addMultiOption(Application_Model_Clientes::CIVIL_VIUVO, 'Viúvo(a)');

		return $civil;
	}

	//--------------------------------------------------------------------------

	private function _getEmail()
	{
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('E-mail')
		->setAttrib('placeholder', 'E-mail')
		->setAttrib('size', '40');

		return $email;
	}

	//--------------------------------------------------------------------------

	private function _getUltimoContato()
	{
		$contato = new Zend_Form_Element_Hidden('ultimo_contato');
		$contato->setValue(date('d/m/Y'));
			
		return $contato;
	}

	//--------------------------------------------------------------------------

	private function _getDataCadastro()
	{
		$dtCadastro = new Zend_Form_Element_Hidden('data_cadastro');
		$dtCadastro->setValue(date('d/m/Y'));

		return $dtCadastro;
	}

	//--------------------------------------------------------------------------

	private function _getSituacao()
	{
		$situacao = new Zend_Form_Element_Hidden('situacao');
		$situacao->setValue(Application_Model_Usuarios::SITUACAO_ATIVO);

		return $situacao;
	}

	//--------------------------------------------------------------------------

	private function _getEnderecoIdCliente()
	{
		$id = new Zend_Form_Element_Hidden('endereco_id');
			
		return $id;
	}

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
		$salvar->setLabel('Cadastrar Cliente');

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
		$this->setAction("/imobiliaria/clientes/alterar/id/{$row->id_cliente}");

		$this->populate($row);

		return $this;
	}

	//--------------------------------------------------------------------------


}
