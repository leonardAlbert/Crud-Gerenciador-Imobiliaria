<?php

class Application_Model_TelefoneImobiliarias extends Zend_Db_Table
{
	protected $_name = 'telefone_imobiliarias';

	protected $_primary = array('id_telefone', 'id_imobiliaria');

	protected $_cols = array('id_telefone', 'id_imobiliaria');

	protected $_telefone;

	//--------------------------------------------------------------------------

	public function __construct()
	{
		$this->_telefone = new Application_Model_Telefone();

		parent::__construct();
	}

	//--------------------------------------------------------------------------

	public function buscar($id)
	{
		$select = $this->select()
				->from($this->_name)
				->where('id_imobiliaria = ?', $id);

		$list = $this->fetchAll($select);

		if($list->current() == null) return null;

		foreach($list as $row)
		{
			$telefones[] = $this->_telefone->find($row->id_telefone)->current();
		}

		return $telefones;
	}

	//--------------------------------------------------------------------------

	// @ TODO
	public function atualizar(array $post)
	{
		$primary = $this->getPrimary();

		$id = array($post['id_telefone'], $post['id_imobiliaria']);

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

	public function getPrimary()
	{
		if(is_array($this->_primary) && count($this->_primary) == 1)
		{
			$this->_primary = array_shift($this->_primary);
		}

		return $this->_primary;
	}

	//--------------------------------------------------------------------------

}