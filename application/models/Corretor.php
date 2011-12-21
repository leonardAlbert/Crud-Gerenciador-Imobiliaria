<?php
/**
 * Application_Model_Corretor
 * 
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Corretor extends Zend_Db_Table
{
	/**
	 * Nome da tabela utilizada, corretor
	 * @var unknown_type
	 */
    protected $_name = 'corretores';
    
    //--------------------------------------------------------------------------
	
	/**
	* Constantes utilizadas para identificar a situação deste usuário
	*
	* @var int
	*/
	const SITUACAO_ATIVO = 1;
	const SITUACAO_INATIVO = 2;
	
	//--------------------------------------------------------------------------
		
	/**
	* Constantes utilizadas para identificar a situação deste usuário
	*
	* @var int
	*/
	const SEXO_MASCULINO = 1;
	const SEXO_FEMININO = 2;
	
	//--------------------------------------------------------------------------
	
    /**
     * Chave primaria da tabela Corretor
     * @var unknown_type
     */
	protected $_primary = 'id_corretor';
	
	//--------------------------------------------------------------------------
		
	protected $_telefoneCorretor;
	
	//--------------------------------------------------------------------------
	
	protected $_telefone;
	protected $_endereco;
	
	//--------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->_telefone = new Application_Model_Telefone();
		$this->_endereco = new Application_Model_Endereco();
		
		$this->_telefoneCorretor = new Application_Model_TelefoneCorretores();
		
		parent::__construct();
	}
	
	//--------------------------------------------------------------------------

	protected $_cols = array(
		'id_corretor',
		'nome',
		'sexo',
		'creci',
		'situacao',
		'data_cadastro',
		'endereco_id'
	);
	
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
    	$corretor = $this->_filtrar($dados);
    	
    	try 
		{
			$endereco = $this->_endereco->cadastrar($dados);
			$telefone = $this->_telefone->cadastrar($dados);

			$corretor['endereco_id'] = $endereco;
			$corretor = $this->insert($corretor);
			
			$data = array(
				'id_corretor' 	=> $corretor,
				'id_telefone' 	=> $telefone
			);
			
			$telefoneCliente = $this->_telefoneCorretor->insert($data);
		}
		catch(Exception $e)
		{
			$endereco = FALSE;
			$telefone = FALSE;
			$telefoneCliente = FALSE;
			
			return "O Corretor não pode ser cadastrado: {$e->getMessage()}";
    	}
    	
    	
    	return $corretor;
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
 		
 		if($list->current() == null) return null;
 		
 		$corretores = array();
 		
 		foreach($list as $row)
 		{
 			$corretores[] = new CorretorMapper($row);
 		}
 		
 		return $corretores;
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
 	
 	public function buscar($id)
 	{
	 	$where = array('id_corretor = ?' => (int) $id);
	 		
	 	$corretor = $this->listar($where);
	 		
	 	if($corretor == null) return null;
	 		
	 	return array_shift($corretor);
 	}
 	
 	//--------------------------------------------------------------------------
 	
 	
}