<?php

class Application_Model_TelefoneCorretores extends Zend_Db_Table
{
	protected $_name = 'telefone_corretores';

	protected $_primary = array('id_telefone', 'id_corretor');

	protected $_cols = array('id_telefone', 'id_corretor');

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
				->where('id_corretor = ?', $id);

		$list = $this->fetchAll($select);

		if($list->current() == null) return null;

		foreach($list as $row)
		{
			$telefones[] = $this->_telefone->find($row->id_telefone)->current();
		}

		return $telefones;
	}

	//--------------------------------------------------------------------------

	public function atualizar(array $post)
	{
		$id = $post['id_telefone'];
		
		try 
		{
			$update = $this->_telefone->atualizar($post, $id);
		}
		catch(Exception $e)
		{
			$update = FALSE;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			return "O endereco não pode ser atualizado {$e->getMessage()}";
		}
		
		return $update;
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