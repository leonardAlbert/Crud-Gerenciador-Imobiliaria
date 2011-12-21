<?php

class Application_Model_InteresseCliente extends Zend_Db_Table
{
	/**
	 * Nome da tabela utilizada, Clientes
	 * @var unknown_type
	 */
	protected $_name = 'interesse_cliente';

	//--------------------------------------------------------------------------

	/**
	 * Chave primaria da tabela clientes
	 * @var unknown_type
	 *
	 */
	protected $_primary = 'id_interesse';

	//--------------------------------------------------------------------------

	protected $_cliente;

	//--------------------------------------------------------------------------
	
	const SITUACAO_ATIVO = 1;
	const SITUACAO_INATIVO = 2;
	
	//--------------------------------------------------------------------------

	const TIPO_CASA		= 1;
	const TIPO_TERRENO	= 2;
	const TIPO_PREDIO	= 3;

	//--------------------------------------------------------------------------

	const STATUS_ALUGADO 	= 1;
	const STATUS_ALUGANDO	= 2;
	const STATUS_SUSPENSO	= 3;
	const STATUS_VENDIDO	= 4;
	const STATUS_VENDENDO 	= 5;

	//--------------------------------------------------------------------------

	const CATEGORIA_COMERCIAL 	= 1;
	const CATEGORIA_INDUSTRIAL 	= 2;
	const CATEGORIA_RESIDENCIAL = 3;

	//--------------------------------------------------------------------------

	const ENDERECO_RESIDENCIAL	= 1;
	const ENDERECO_COMERCIAL	= 2;
	const ENDERECO_INDUSTRIAL	= 3;

	//--------------------------------------------------------------------------

	const ZONA_NORTE	= 1;
	const ZONA_SUL		= 2;
	const ZONA_LESTE	= 3;
	const ZONA_OESTE	= 4;

	//--------------------------------------------------------------------------
	
	const PESSOA_FISICA = 1;
	const PESSOA_JURIDICA = 2;
	
	//--------------------------------------------------------------------------
	
	const SEXO_MASCULINO = 1;
	const SEXO_FEMININO = 2;
	
	//--------------------------------------------------------------------------
	
	const CADASTRO_LOCATARIO = 1;
	const CADASTRO_LOCADOR = 2;
	const CADASTRO_FIADOR = 3;
	const CADASTRO_PROPRIETARIO = 4;
	const CADASTRO_COMPRADOR =5;
	
	//--------------------------------------------------------------------------
	
	const CIVIL_SOLTEIRO	= 1;
	const CIVIL_CASADO		= 2;
	const CIVIL_SEPARADO	= 3;
	const CIVIL_DIVORCIADO	= 4;
	const CIVIL_VIUVO		= 5;
	
	//--------------------------------------------------------------------------
	
	const CHAVE_CLIENTE 	= 1;
	const CHAVE_CONSTRUTORA = 2;
	const CHAVE_IMOBILIARIA = 3;
	const CHAVE_PORTARIA 	= 4;
	const CHAVE_VIZINHO 	= 5;
	const CHAVE_ZELADOR 	= 6;
	
	//--------------------------------------------------------------------------

	protected $_cols = array(
		'id_interesse',
		'tipo_imovel',
		'status_imovel',
		'categoria_imovel',
		'valor_imovel',
		'ano_construcao',
		'dormitorios',
		'suite',
		'vaga_carro',
		'metragem_terreno',
		'valor_iptu',
		'valor_condominio',
		'area_lazer',
		'area_construida',
		'total_comodos',
		'area_servico',
		'zona',
		'bairro',
		'cidade',
		'estado',
		'tipo_endereco'
	);

	//--------------------------------------------------------------------------

	public function __construct()
	{
		$this->_cliente = new Application_Model_Clientes();

		parent::__construct();
	}

	//--------------------------------------------------------------------------

	public function getCols()
	{
		return $this->_cols;
	}

	//--------------------------------------------------------------------------

	public function getPrimary()
	{
		if(is_array($this->_primary) && count($this->_primary) == 1)
		{
			$this->_primary = array_shift($this->_primary);
		}

		return $this->_primary;
	}

	//--------------------------------------------------------------------------

	public function cadastrar($dados)
	{
		$dados = $this->_filtrar($dados);

		try
		{
			$interesse = $this->insert($dados);
		}
		catch(Exception $e)
		{
			$interesse = FALSE;

			return "O interesse do cliente não pode ser cadastrado: {$e->getMessage()}";
		}

		return $interesse;
	}

	//--------------------------------------------------------------------------

	protected function _filtrar($dados)
	{
		$data = array();

		foreach($this->_cols as $value)
		{
			if( array_key_exists($value, $dados) )
			{
				$data[$value] = $dados[$value];
			}
		}

		unset($data[$this->_primary]);

		return $data;
	}

	//--------------------------------------------------------------------------


	public function listar(array $where = NULL, $order = NULL, $situacao = NULL, array $like = NULL)
	{
		$select = $this->select()->from($this->_name);

		if($where != null)
		{
			foreach($where as $coluna => $valor)
			{
				$select->where($coluna, $valor);
			}
		}

		if($like != null)
		{
			foreach($like as $coluna => $valor)
			{
				$select->where("$coluna LIKE ?", $valor.'%');
			}

		}

		if($order != null) $select->order($order);

		if($situacao != NULL) $select->where('situacao = ?', $situacao);

		$list = $this->fetchAll($select);

		if($list->current() == NULL) return NULL;

		return $list;
	}

	//--------------------------------------------------------------------------

	public static function getZona($flag)
	{
		switch ($flag) 
		{
			case self::ZONA_NORTE:
				return 'Zona Norte';
				break;

			case self::ZONA_SUL:
				return 'Zona Sul';
				break;

			case self::ZONA_LESTE:
				return 'Zona Leste';
				break;

			case self::ZONA_OESTE:
				return 'Zona OesteS';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public static function getTipoEndereco($flag)
	{
		switch ($flag) 
		{
			case self::ENDERECO_RESIDENCIAL:
				return 'Residencial';
				break;

			case self::ENDERECO_COMERCIAL:
				return 'Comercial';
				break;

			case self::ENDERECO_INDUSTRIAL:
				return 'Industrial';
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
	public static function getSituacao($flag)
	{
		switch ($flag)
		{
			case self::SITUACAO_ATIVO:
				return 'Ativo';
				break;
					
			case self::SITUACAO_INATIVO:
				return 'Inativo';
				break;
					
			default:
				return 'Status Desconhecido';
				break;
		}
	}

	//--------------------------------------------------------------------------

	public static function getTipoImovel($flag)
	{
		switch ($flag)
		{
			case self::TIPO_CASA:
				return 'Casa';
				break;

			case self::TIPO_TERRENO:
				return 'Terreno';
				break;

			case self::TIPO_PREDIO:
				return 'Prédio';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public static function getStatusImovel($flag)
	{
		switch ($flag)
		{
			case self::STATUS_ALUGADO:
				return 'Alugado';
				break;

			case self::STATUS_ALUGANDO:
				return 'Alugando';
				break;

			case self::STATUS_SUSPENSO:
				return 'Suspenso';
				break;

			case self::STATUS_VENDIDO:
				return 'Vendido';
				break;

			case self::STATUS_VENDENDO:
				return 'Vendendo';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public static function getCategoriaImovel($flag)
	{
		switch ($flag)
		{
			case self::CATEGORIA_COMERCIAL:
				return 'Comercial';
				break;

			case self::CATEGORIA_INDUSTRIAL:
				return 'Industria';
				break;

			case self::CATEGORIA_RESIDENCIAL:
				return 'Residêncial';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

	public static function getChaveImovel($flag)
	{
		switch ($this->getImovel()->local_chave)
		{
			case self::CHAVE_CLIENTE:
				return 'Cliente';
				break;

			case self::CHAVE_CONSTRUTORA:
				return 'Construtora';
				break;

			case self::CHAVE_IMOBILIARIA:
				return 'Imobiliaria';
				break;

			case self::CHAVE_PORTARIA:
				return 'Portaria';
				break;

			case self::CHAVE_VIZINHO:
				return 'Vizinho';
				break;

			case self::CHAVE_ZELADOR:
				return 'Zelador';
				break;

			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

}