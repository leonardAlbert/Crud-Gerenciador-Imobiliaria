<?php
/**
 * CorretorMapper
 *
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class ClientesMapper
{
	//--------------------------------------------------------------------------

	protected $_modelClientes;
	protected $_modelTelefone;
	protected $_modelEndereco;

	//--------------------------------------------------------------------------

	protected $_telefone;
	protected $_endereco;
	protected $_cliente;

	//--------------------------------------------------------------------------

	public function __construct(Zend_Db_Table_Row $cliente)
	{
		$this->_modelTelefone = new Application_Model_TelefoneClientes();
		$this->_modelEndereco = new Application_Model_Endereco();

		return $this->_mapper($cliente);
	}

	//--------------------------------------------------------------------------

	protected function _mapper(Zend_Db_Table_Row $cliente)
	{
		$this->_cliente = $cliente;
		$id = $cliente->id_cliente;
		$idEndereco = $cliente->endereco_id;

		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$telefone = $this->_modelTelefone->buscar($id);

		foreach ($telefone as $row)
		{
			$arrayTelefone[$row->id_telefone] = $row;
		}
		$this->_telefone = array_shift($arrayTelefone);

		$arrayEndereco = array();
		foreach ($endereco as $row)
		{
			$arrayEndereco = $row;
		}
		$this->_endereco = $arrayEndereco;

		return $this;
	}

	//--------------------------------------------------------------------------

	public function getCliente()
	{
		return $this->_cliente;
	}

	//--------------------------------------------------------------------------

	public function getTelefone()
	{
		return $this->_telefone;
	}

	//--------------------------------------------------------------------------

	public function getEndereco()
	{
		return $this->_endereco;
	}

	//--------------------------------------------------------------------------

	public function setCliente($Cliente)
	{
		$this->_cliente = $Cliente;
	}

	//--------------------------------------------------------------------------

	public function setTelefone($telefone)
	{
		$this->_telefone = $telefone;
	}

	//--------------------------------------------------------------------------

	public function setEndereco($endereco)
	{
		$this->_endereco = $endereco;
	}

	//--------------------------------------------------------------------------

	public function getTipoCadastro()
	{
		switch ($this->getCliente()->tipo_cadastro)
		{
			case Application_Model_Clientes::CADASTRO_LOCATARIO:
				return 'Locatario';
				break;

			case Application_Model_Clientes::CADASTRO_LOCADOR:
				return 'Locador';
				break;

			case Application_Model_Clientes::CADASTRO_FIADOR:
				return 'Fiador';
				break;

			case Application_Model_Clientes::CADASTRO_PROPRIETARIO:
				return 'Proprietario';
				break;
					
			case Application_Model_Clientes::CADASTRO_COMPRADOR:
				return 'Comprador';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public function getTipoPessoa()
	{
		switch ($this->getCliente()->tipo_pessoa)
		{
			case Application_Model_Clientes::PESSOA_FISICA:
				return 'Pessoa Fisica';
				break;

			case Application_Model_Clientes::PESSOA_JURIDICA:
				return 'Pessoa Juridica';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public function getTipoEstadoCivil()
	{
		switch ($this->getCliente()->estado_civil)
		{
			case Application_Model_Clientes::CIVIL_SOLTEIRO:
				return 'Solteiro(a)';
				break;

			case Application_Model_Clientes::CIVIL_CASADO:
				return 'Casado(a)';
				break;

			case Application_Model_Clientes::CIVIL_SEPARADO:
				return 'Separado(a)';
				break;

			case Application_Model_Clientes::CIVIL_DIVORCIADO:
				return 'Divorciado(a)';
				break;

			case Application_Model_Clientes::CIVIL_VIUVO:
				return 'Viúvo(a)';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	/**
	 * Retorna a situação do usuário
	 *
	 * @param  int     $situacao
	 * @return string
	 */
	public function getSituacao()
	{
		switch ($this->getCliente()->situacao)
		{
			case Application_Model_Clientes::SITUACAO_ATIVO:
				return 'Ativo';
				break;

			case Application_Model_Clientes::SITUACAO_INATIVO:
				return 'Inativo';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	/**
	 * Retorna a situação do usuário
	 *
	 * @param  int     $situacao
	 * @return string
	 */
	public function getSexo()
	{
		switch ($this->getCliente()->sexo)
		{
			case Application_Model_Clientes::SEXO_MASCULINO:
				return 'Masculino';
				break;

			case Application_Model_Clientes::SEXO_FEMININO:
				return 'Feminino';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	/**
	 * Retorna a situação do usuário
	 *
	 * @param  int     $situacao
	 * @return string
	 */
	public function getTipoEndereco()
	{
		switch ($this->getCliente()->tipo_endereco)
		{
			case Application_Model_Endereco::ENDERECO_COMERCIAL;
			return 'Comercial';
			break;

			case Application_Model_Endereco::ENDERECO_INDUSTRIAL;
			return 'Industrial';
			break;
				
			case Application_Model_Endereco::ENDERECO_RESIDENCIAL;
			return 'Residêncial';
			break;
				
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public function getZona()
	{
		switch ($this->getEndereco()->zona) 
		{
			case Application_Model_Endereco::ZONA_NORTE:
				return 'Zona Norte';
				break;

			case Application_Model_Endereco::ZONA_SUL:
				return 'Zona Sul';
				break;

			case Application_Model_Endereco::ZONA_LESTE:
				return 'Zona Leste';
				break;

			case Application_Model_Endereco::ZONA_OESTE:
				return 'Zona OesteS';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

}