<?php
/**
 * Application_Model_Imobiliaria
 *
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Imobiliaria extends Zend_Db_Table
{
	//--------------------------------------------------------------------------

	/**
	 * Constantes utilizadas para identificar a situação
	 *
	 * @var int
	 */
	const SITUACAO_ATIVO = 1;
	const SITUACAO_INATIVO = 2;

	//--------------------------------------------------------------------------

	/**
	 * Nome da tabela utilizada, imobiliarias
	 * @var unknown_type
	 */
	protected $_name = 'imobiliarias';

	//--------------------------------------------------------------------------

	/**
	 * Chave primaria da tabela imobiliarias
	 * @var unknown_type
	 */
	protected $_primary = 'id_imobiliaria';

	//--------------------------------------------------------------------------

	protected $_telefone;
	protected $_endereco;

	//--------------------------------------------------------------------------

	protected $_telefoneImobiliaria;

	//--------------------------------------------------------------------------

	protected $_cols = array(
		'id_imobiliaria',
		'nome',
		'razao_social',
		'site',
		'cnpj',
		'representante',
		'situacao',
		'endereco_id'
	);

	//--------------------------------------------------------------------------

	public function __construct()
	{
		$this->_telefone = new Application_Model_Telefone();
		$this->_endereco = new Application_Model_Endereco();

		$this->_fotos = new Application_Model_Fotos();

		$this->_telefoneImobiliaria = new Application_Model_TelefoneImobiliarias();

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
		$imobiliaria = $this->_filtrar($dados);

		try
		{
			$endereco = $this->_endereco->cadastrar($dados);
			$telefone = $this->_telefone->cadastrar($dados);
				
			$imobiliaria['endereco_id'] = $endereco;
			$imobiliaria = $this->insert($imobiliaria);

			$data = array(
				'id_imobiliaria' 	=> $imobiliaria,
				'id_telefone' 		=> $telefone
			);
				
			$telefoneCorretor = $this->_telefoneImobiliaria->insert($data);
		}
		catch(Exception $e)
		{
			$endereco = FALSE;
			$telefone = FALSE;
			$imobiliaria = FALSE;
			$telefoneCorretor = FALSE;

			return "O cliente não pode ser cadastrado: {$e->getMessage()}";
		}

		return $imobiliaria;
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

	private function _dadosCadastro($dados)
	{
		foreach($this->_cols as $key => $value)
		{
			if( ! isset($dados[$key]) )
			unset($dados[$key]);
		}

		return $dados;
	}

	//--------------------------------------------------------------------------


	public function listar(array $where = NULL, $order = NULL, $situacao = NULL)
	{
		$select = $this->select()->from($this->_name);

		if($where != null)
		{
			foreach($where as $coluna => $valor)
			{
				$select->where($coluna, $valor);
			}
		}

		if($order != null) $select->order($order);

		if($situacao != NULL) $select->where('situacao = ?', $situacao);

		$list = $this->fetchAll($select);
			
		if($list->current() == NULL) return NULL;

		$imobiliaria = array();

		foreach($list as $row)
		{
			$imobiliaria[] = new ImobiliariaMapper($row);
		}
			
		return $imobiliaria;
	}

	//--------------------------------------------------------------------------

	public function buscar($id)
	{
		$where = array('id_imobiliaria = ?' => (int) $id);

		$imobiliaria = $this->listar($where);

		if($imobiliaria == NULL) return NULL;

		return array_shift($imobiliaria);
	}

	//--------------------------------------------------------------------------

	//--------------------------------------------------------------------------

}