<?php
/**
 * Imobiliaria_Form_Imovel
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_Form_Imovel extends Zend_Form
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
		$this->setName('imovel');

		$this->imovel();
	}

	//--------------------------------------------------------------------------

	public function imovel()
	{
		$this->addElements(
		array(
		$this->_getId(),
		//$this->_getIdFoto(),
		$this->_getIdEndereco(),
		$this->_getEnderecoIdImovel(),
		$this->_getDataCadastro(),

		$this->_getCategoriaImovel(),
		$this->_getImovel(),
		$this->_getTipoImovel(),
		$this->_getDormitorios(),
		$this->_getVagaCarro(),
		$this->_getSuite(),

		$this->_getAnoContrucao(),
		$this->_getValorImovel(),
		$this->_getStatusImovel(),
		$this->_getPromotor(),
		$this->_getUltimaVisita(),
		$this->_getLocalChave(),

		$this->_getMetragemTerreno(),
		$this->_getValorIptu(),
		$this->_getValorCondominio(),
		$this->_getAreaLaser(),
		$this->_getAreaConstruida(),
		$this->_getTotalComodos(),
		$this->_getAreaServico(),

		$this->_getTipoEndereco(),
		$this->_getLogradouro(),
		$this->_getNumeroEnd(),
		$this->_getBairro(),
		$this->_getCep(),
		$this->_getZona(),
		$this->_getCidade(),
		$this->_getEstado(),

		$this->_getAnotacoes(),
		//$this->_getFoto(),

		$this->_getSituacao(),
		$this->_getSubmit()
		)
		);
	}

	//--------------------------------------------------------------------------

	private function _getId()
	{
		$id = new Zend_Form_Element_Hidden('id_imovel');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getImovel()
	{
		$imovel = new Zend_Form_Element_Text('imovel');
		$imovel->setLabel('Imóvel')
		->setAttrib('placeholder', 'Imóvel')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '25');

		return $imovel;
	}

	//--------------------------------------------------------------------------

	private function _getPromotor()
	{
		$promotor = new Zend_Form_Element_Select('promotor');
		$promotor->setLabel('Corretor')
		->setAttrib('placeholder', 'Corretor')
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione");

		$corretores = new Application_Model_Corretor();

		foreach($corretores->fetchAll(null, 'nome') as $corretor)
		{
			$promotor->addMultiOption($corretor->id_corretor, $corretor->nome);
		}
			
		return $promotor;
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

	private function _getTipoImovel()
	{
		$tipoImovel = new Zend_Form_Element_Select('tipo_imovel');
		$tipoImovel->setLabel('Tipo Imóvel')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Imovel::TIPO_CASA, 'Casa')
		->addMultiOption(Application_Model_Imovel::TIPO_PREDIO, 'Predio')
		->addMultiOption(Application_Model_Imovel::TIPO_TERRENO, 'Terreno');

		return $tipoImovel;
	}

	//--------------------------------------------------------------------------

	private function _getStatusImovel()
	{
		$stImovel = new Zend_Form_Element_Select('status_imovel');
		$stImovel->setLabel('Status Imóvel')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Imovel::STATUS_ALUGADO, 'Alugado')
		->addMultiOption(Application_Model_Imovel::STATUS_ALUGANDO, 'Alugado')
		->addMultiOption(Application_Model_Imovel::STATUS_SUSPENSO, 'Suspenso')
		->addMultiOption(Application_Model_Imovel::STATUS_VENDENDO, 'Vendendo')
		->addMultiOption(Application_Model_Imovel::STATUS_VENDIDO, 'Vendido');

		return $stImovel;
	}

	//--------------------------------------------------------------------------

	private function _getCategoriaImovel()
	{
		$categoria = new Zend_Form_Element_Select('categoria_imovel');
		$categoria->setLabel('Categoria')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Imovel::CATEGORIA_COMERCIAL, 'Comercial')
		->addMultiOption(Application_Model_Imovel::CATEGORIA_INDUSTRIAL, 'Industrial')
		->addMultiOption(Application_Model_Imovel::CATEGORIA_RESIDENCIAL, 'Residêncial');

		return $categoria;
	}

	//--------------------------------------------------------------------------

	private function _getLocalChave()
	{
		$chave = new Zend_Form_Element_Select('local_chave');
		$chave->setLabel('Local Chave')
		->setRequired(true)
		->setAllowEmpty(false)
		->addMultiOption("", "Selecione")
		->addMultiOption(Application_Model_Imovel::CHAVE_CLIENTE, 'Cliente')
		->addMultiOption(Application_Model_Imovel::CHAVE_CONSTRUTORA, 'Construtora')
		->addMultiOption(Application_Model_Imovel::CHAVE_IMOBILIARIA, 'Imobiliaria')
		->addMultiOption(Application_Model_Imovel::CHAVE_PORTARIA, 'Portaria')
		->addMultiOption(Application_Model_Imovel::CHAVE_VIZINHO, 'Vizinho')
		->addMultiOption(Application_Model_Imovel::CHAVE_ZELADOR, 'Zelador');

		return $chave;
	}

	//--------------------------------------------------------------------------

	private function _getDataCadastro()
	{
		$dtCadastro = new Zend_Form_Element_Hidden('data_cadastro');
		$dtCadastro->setValue(date('d/m/Y'));

		return $dtCadastro;
	}

	//--------------------------------------------------------------------------

	private function _getEnderecoIdImovel()
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

	private function _getUltimaVisita()
	{
		$contato = new Zend_Form_Element_Hidden('ultima_visita');
		$contato->setValue(date('d/m/Y'));
		
		return $contato;
	}

	//--------------------------------------------------------------------------

	private function _getAnoContrucao()
	{
		$dt_nasc = new Zend_Form_Element_Text('ano_construcao');
		$dt_nasc->setLabel('Ano de Construção')
		->setAttrib('placeholder', 'Ano Contrução')
		->setAttrib('class', 'quatro_digitos')
		->setAllowEmpty(false)
		->setAttrib('size', '12');

		return $dt_nasc;
	}

	//--------------------------------------------------------------------------

	private function _getSuite()
	{
		$suite = new Zend_Form_Element_Text('suite');
		$suite->setLabel('Suíte')
		->setAttrib('class', 'dois_digitos')
		->setAttrib('placeholder', 'Suíte')
		->setAttrib('size', '10');

		return $suite;
	}

	//--------------------------------------------------------------------------

	private function _getVagaCarro()
	{
		$carro = new Zend_Form_Element_Text('vagas_carro');
		$carro->setLabel('Vaga Carro')
		->setAttrib('class', 'dois_digitos')
		->setAttrib('placeholder', 'Vaga Carros')
		->setAttrib('size', '10');

		return $carro;
	}

	//--------------------------------------------------------------------------

	private function _getDormitorios()
	{
		$dormitorio = new Zend_Form_Element_Text('dormitorios');
		$dormitorio->setLabel('Dormitorios')
		->setAttrib('class', 'dois_digitos')
		->setAttrib('placeholder', 'Dormitorios')
		->setAttrib('size', '10');

		return $dormitorio;
	}

	//--------------------------------------------------------------------------

	private function _getValorImovel()
	{
		$valor = new Zend_Form_Element_Text('valor_imovel');
		$valor->setLabel('Valor Imóvel')
		->setAttrib('placeholder', 'Valor Imóvel')
		->setAttrib('class', 'valor_dinheiro')
		->setAttrib('size', '12');

		return $valor;
	}

	//--------------------------------------------------------------------------

	private function _getMetragemTerreno()
	{
		$metragem = new Zend_Form_Element_Text('metragem_terreno');
		$metragem->setLabel('Terreno (m²)')
		->setAttrib('placeholder', 'Metragem')
		->setAttrib('class', 'seis_digitos')
		->setAttrib('size', '10');

		return $metragem;
	}

	//--------------------------------------------------------------------------

	private function _getValorIptu()
	{
		$valor = new Zend_Form_Element_Text('valor_iptu');
		$valor->setLabel('Valor IPTU')
		->setAttrib('placeholder', 'Valor IPTU')
		->setAttrib('class', 'valor_dinheiro')
		->setAttrib('size', '12');

		return $valor;
	}

	//--------------------------------------------------------------------------

	private function _getValorCondominio()
	{
		$valor = new Zend_Form_Element_Text('valor_condominio');
		$valor->setLabel('Valor Condomínio')
		->setAttrib('placeholder', 'Condomínio')
		->setAttrib('class', 'valor_dinheiro')
		->setAttrib('size', '12');

		return $valor;
	}

	//--------------------------------------------------------------------------

	private function _getAreaLaser()
	{
		$laser = new Zend_Form_Element_Text('area_lazer');
		$laser->setLabel('Área de Lazer (m²)')
		->setAttrib('placeholder', 'Área de Lazer')
		->setAttrib('class', 'seis_digitos')
		->setAttrib('size', '12');

		return $laser;
	}

	//--------------------------------------------------------------------------

	private function _getAreaConstruida()
	{
		$area = new Zend_Form_Element_Text('area_construida');
		$area->setLabel('Área Construida (m²)')
		->setAttrib('placeholder', 'Área Construida')
		->setAttrib('class', 'seis_digitos')
		->setAttrib('size', '12');

		return $area;
	}

	//--------------------------------------------------------------------------

	private function _getTotalComodos()
	{
		$comodo = new Zend_Form_Element_Text('total_comodos');
		$comodo->setLabel('Total Cômodos')
		->setAttrib('class', 'dois_digitos')
		->setAttrib('placeholder', 'Total Cômodos')
		->setAttrib('size', '12');

		return $comodo;
	}

	//--------------------------------------------------------------------------

	private function _getAreaServico()
	{
		$servico = new Zend_Form_Element_Text('area_servico');
		$servico->setLabel('Área Serviço')
		->setAttrib('placeholder', 'Área Serviço')
		->setAttrib('size', '12');

		return $servico;
	}

	//--------------------------------------------------------------------------


	//--------------------------------------------------------------------------
	// Form Foto - Form Foto - Form Foto - Form Foto - Form Foto - Form Foto
	//--------------------------------------------------------------------------

	private function _getIdFoto()
	{
		$id = new Zend_Form_Element_Hidden('id_foto');
			
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getFoto()
	{
		$foto = new Zend_Form_Element_File('foto');
		$foto->setLabel('Foto');

		return $foto;
	}

	//--------------------------------------------------------------------------
	// Form Foto - Form Foto - Form Foto - Form Foto - Form Foto - Form Foto
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


	private function _getSubmit()
	{
		$salvar = new Zend_Form_Element_Submit('autenticar');
		$salvar->setLabel('Cadastrar Imóvel');

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
		$this->setAction("/imobiliaria/imovel/alterar/id/{$row->id_imovel}");

		$this->populate($row);

		return $this;
	}

	//--------------------------------------------------------------------------


}
