<?php

class Application_Model_FotoImovel extends Zend_Db_Table
{
	protected $_name = 'fotos_imovel';

	protected $_primary = array('id_foto', 'id_imovel');

	protected $_cols = array('id_foto', 'id_imovel');
	
	protected $_foto;
	
	//--------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->_foto = new Application_Model_Fotos();
		
		parent::__construct();
	}
	
	//--------------------------------------------------------------------------

	public function buscar($id)
    {
 		$select = $this->select()
 					->from($this->_name)
					->where('id_imovel = ?', $id);

 		$list = $this->fetchAll($select);
		 		
 		if($list->current() == null) return null;
 		
 		foreach($list as $row)
 		{
 			$fotos[] = $this->_foto->find($row->id_foto)->current();
 		}
		
 		return $fotos;
    }
    
    //--------------------------------------------------------------------------
    
    // @ TODO
	public function atualizar(array $post)
	{
		$primary = $this->getPrimary();
		
		$id = array($post['id_foto'], $post['id_imovel']);

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
		
	/**
	 * 
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
	
}