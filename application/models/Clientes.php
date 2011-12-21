<?php
/**
 * Application_Model_Clientes
 * 
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Clientes extends Zend_Db_Table
{
	
	/**
	* Nome da tabela utilizada, Clientes
	* @var unknown_type
	*/
	protected $_name = 'clientes';
	    
	//--------------------------------------------------------------------------
	
	/**
	* Chave primaria da tabela clientes
	* @var unknown_type
	*
	*/
	protected $_primary = 'id_cliente';
	
	//--------------------------------------------------------------------------
	
	protected $_telefone;
	protected $_endereco;
	
	//--------------------------------------------------------------------------
	
	protected $_telefoneCliente;
	
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
	 * Contante para determinar o tipo do cliente.
	 * @var integer
	 */
	const PESSOA_FISICA = 1;
	const PESSOA_JURIDICA = 2;
	
	//--------------------------------------------------------------------------
		
	/**
	 * Contante para determinar o sexo do cliente.
	 * @var integer
	 */
	const SEXO_MASCULINO = 1;
	const SEXO_FEMININO = 2;
	
	//--------------------------------------------------------------------------

	/**
	 * Constante para determinar o tipo de cadastro do cliente.
	 * @var integer
	 */
	const CADASTRO_LOCATARIO = 1;
	const CADASTRO_LOCADOR = 2;
	const CADASTRO_FIADOR = 3;
	const CADASTRO_PROPRIETARIO = 4;
	const CADASTRO_COMPRADOR =5;
	
	//--------------------------------------------------------------------------
	
	/**
	 * Constante para determinar o tipo de cadastro do cliente.
	 * @var integer
	 */
	const CIVIL_SOLTEIRO	= 1;
	const CIVIL_CASADO		= 2;
	const CIVIL_SEPARADO	= 3;
	const CIVIL_DIVORCIADO	= 4;
	const CIVIL_VIUVO		= 5; 
	
	//--------------------------------------------------------------------------
	
	protected $_cols = array(
		'id_cliente',
		'tipo_pessoa',
		'tipo_cadastro',
		'nome',
		'cpf',
		'cnpj',
		'rg',
		'data_nascimento',
		'nacionalidade',
		'profissao',
		'sexo',
		'estado_civil',
		'email',
		'anotacoes',
		'data_cadastro',
		'ultimo_contato',
		'situacao',
		'endereco_id',
		'interesse_id'
	);
	
	//--------------------------------------------------------------------------
	
	public function __construct()
	{
		$this->_telefone = new Application_Model_Telefone();
		$this->_endereco = new Application_Model_Endereco();
		
		$this->_fotos = new Application_Model_Fotos();
		
		$this->_telefoneCliente = new Application_Model_TelefoneClientes();
		
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
		$zDate = new Zend_Date($dados['data_cadastro'], Zend_Registry::get('locale'));
		$dados['data_cadastro'] = $zDate->getIso();
		
		$zDate = new Zend_Date($dados['ultimo_contato'], Zend_Registry::get('locale'));
		$dados['ultimo_contato'] = $zDate->getIso();

		$cliente = $this->_filtrar($dados);
		
		try 
		{
			$endereco = $this->_endereco->cadastrar($dados);
			$telefone = $this->_telefone->cadastrar($dados);
			
			$cliente['endereco_id'] = $endereco;
			$cliente = $this->insert($cliente);
			
			$data = array(
				'id_cliente' 	=> $cliente,
				'id_telefone' 	=> $telefone
			);
			
			$telefoneCliente = $this->_telefoneCliente->insert($data);
		}
		catch(Exception $e)
		{
			$endereco = FALSE;
			$telefone = FALSE;
			$cliente = FALSE;
			$telefoneCliente = FALSE;
			
			return "O cliente não pode ser cadastrado: {$e->getMessage()}";
    	}
    	
    	return $cliente;
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
		
 		if($where != NULL)
 		{
 			foreach($where as $coluna => $valor)
 			{
 				$select->where($coluna, $valor);
 			}
 		}
 		
 		if($order != NULL) $select->order($order);
 		
 		if($situacao != NULL) $select->where('situacao = ?', $situacao);
 		
 		$list = $this->fetchAll($select);
 		
 		if($list->current() == NULL) return NULL;
 		
 		$clientes = array();
 		
 		foreach($list as $row)
 		{
 			$clientes[] = new ClientesMapper($row);
 		}
 		
 		return $clientes;
 	}
 	
 	//--------------------------------------------------------------------------
 	
 	public function buscar($id)
 	{
 		$where = array('id_cliente = ?' => (int) $id);
 		
 		$cliente = $this->listar($where);
 		
 		if($cliente == null) return null;
 		
 		return array_shift($cliente);
 	}
 	
 	//--------------------------------------------------------------------------
 	
 	// @TODO Adicionar a "Situacao" do cliente como self::INATIVO
 	//--------------------------------------------------------------------------

}