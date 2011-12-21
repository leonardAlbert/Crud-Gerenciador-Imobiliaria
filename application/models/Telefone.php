<?php
/**
 * Application_Model_Telefone
 * 
 * Entidade responsável pela persistencia dos dados
 * 
 * @package TCC
 * @author Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since 1.0
 * 
 */
class Application_Model_Telefone extends Zend_Db_Table
{
	
	/**
	 * Nome da tabela utilizada, Telefone
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_name = 'telefone';
	
	//--------------------------------------------------------------------------
	
	/**
	 * Chave primaria da tabela telefone
	 */
	protected $_primary = 'id_telefone';
	
	//--------------------------------------------------------------------------

	/**
	 * Constante para determinar o tipo de telefone gravado no banco.
	 * @var integer
	 */
	const TELEFONE_RESIDENCIAL = 1;
	const TELEFONE_COMERCIAL = 2;
	const TELEFONE_CELULAR = 3;
	const TELEFONE_RECADO = 4;
	const TELEFONE_OUTRO = 5;
	
	//--------------------------------------------------------------------------
	/**
	 * 
	 * Enter description here ...
	 * @var unknown_type
	 */	
	protected $_cols = array (
		'id_telefone',
		'telefone',
		'tipo_telefone',
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
	
	public static function getTipoTelefone($flag)
	{
		switch ($flag) {
			case self::TELEFONE_RESIDENCIAL:
				return 'Residencial';
				break;
	
			case self::TELEFONE_COMERCIAL:
				return 'Comercial';
				break;
					
			case self::TELEFONE_CELULAR:
				return 'Celular';
				break;
					
			case self::TELEFONE_RECADO:
				return 'Recado';
				break;
					
			case self::TELEFONE_OUTRO:
				return 'Outro';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}
	
	//--------------------------------------------------------------------------
	
	public function cadastrar($dados)
	{
		$telefone = $this->_filtrar($dados);
		try 
		{
			$idTelefone = $this->insert($telefone);
		}
		catch(Exception $e)
		{
			return "O telefone não pode ser cadastrado {$e->getMessage()}";
		}
		
		return $idTelefone;
	}
	
	//--------------------------------------------------------------------------
	
	public function atualizar($dados, $id)
	{
		$primary = $this->getPrimary();
		$dados = $this->_filtrar($dados);
		$where = $this->getAdapter()->quoteInto("{$primary} = ?", $id);
		
		try 
		{
			$telefone = $this->update($dados, $where);
		}
		catch(Exception $e)
		{
			$telefone = FALSE;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			return "O endereco não pode ser atualizado {$e->getMessage()}";
		}
		
		return $telefone;
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
    
}