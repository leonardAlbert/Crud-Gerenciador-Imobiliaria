<?php
/**
 *
 */
class Application_Model_Endereco extends Zend_Db_Table
{

	//--------------------------------------------------------------------------
	/**
	*
	* Enter description here ...
	* @var unknown_type
	*/
	protected $_name = 'endereco';

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_primary = 'id_endereco';

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const ENDERECO_RESIDENCIAL	= 1;
	const ENDERECO_COMERCIAL	= 2;
	const ENDERECO_INDUSTRIAL	= 3;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const ZONA_NORTE	= 1;
	const ZONA_SUL		= 2;
	const ZONA_LESTE	= 3;
	const ZONA_OESTE	= 4;

	//--------------------------------------------------------------------------

	protected $_cols = array(
		'id_endereco',
		'logradouro',
		'numero_end',
		'zona',
		'bairro',
		'cep',
		'cidade',
		'estado',
		'tipo_endereco'
	);

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 */
	public function getCols()
	{
		return $this->_cols;
	}

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 */
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
		$endereco = $this->_filtrar($dados);

		try
		{
			$idEndereco = $this->insert($endereco);
		}
		catch(Exception $e)
		{
			$idEndereco = FALSE;
			return "O endereco não pode ser cadastrado {$e->getMessage()}";
		}

		return $idEndereco;
	}

	//--------------------------------------------------------------------------

	public function _filtrar($dados)
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

	public function buscar($id, $order = null)
	{
		$select = $this->select()->from($this->_name)
		->where('id_endereco = ?', $id);

		if($order != null) $select->order($order);

		$list = $this->fetchAll($select);

		return $list;
	}



	//--------------------------------------------------------------------------

	public static function getZona($flag)
	{
		switch ($flag) {
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
		switch ($flag) {
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

	public function atualizar(array $post)
	{
		$id = $post['endereco_id'];
		$primary = $this->getPrimary();

		$where = $this->getAdapter()->quoteInto("{$primary} = ?", $id);
		$dados = $this->_filtrar($post);

		try
		{
			$update = $this->update($dados, $where);
		}
		catch(Exception $e)
		{
			$update = FALSE;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			return "O endereco não pode ser atualizado {$e->getMessage()}";
		}

		return $id;
	}


	//--------------------------------------------------------------------------

	public function buscaLike(array $like)
	{
		$like = $this->_filtrar($like);
		if ($like == NULL) return NULL;

		$select = $this->select()->from($this->_name);
		foreach($like as $coluna => $valor)
		{
			$select->where("$coluna LIKE ?", "%{$valor}%");
		}

		return $this->fetchAll($select);
	}

	//--------------------------------------------------------------------------


}