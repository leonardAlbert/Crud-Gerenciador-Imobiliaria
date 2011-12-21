<?php
/**
 * Controller Abstract
 * 
 * Essa clase abstrata é utilizada para CRUDs em geral, acoplando em si mesma
 * a funcionalidade do processamento dos dados vindos de um formulário para
 * ser inserido no banco bem como o controle de acesso á este controlador.
 *
 * @package 	library
 */
abstract class Controller extends Zend_Controller_Action
{
	/**
	 * Formulário responsável por essa entidade
	 *
	 * @access  protected
	 */
	protected $_form;

	//--------------------------------------------------------------------------

	/**
	 * Model responsável por essa entidade
	 *
	 * @access  protected
	 */
	protected $_model;

	//--------------------------------------------------------------------------

	/**
	 * Objeto Flash Messenger
	 *
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger;

	//--------------------------------------------------------------------------

	/**
	 * Objeto Zend Cache
	 *
	 * @var Zend_Cache
	 */
	protected $_cache;

	//--------------------------------------------------------------------------

	/**
	 * Guarda o valor do cache de list para ser manipulado.
	 *
	 * @var string
	 */
	protected $_cacheList;

	//--------------------------------------------------------------------------

	/**
	 * Guarda o caminho do método listar desse controlador
	 *
	 * Muito utilizado quando alterado ou inserido um novo registro
	 *
	 * @var string
	 */
	protected $_listagem;
	
	//--------------------------------------------------------------------------
	
	/**
	 * Instancia do banco de dados
	 *
	 * @access  protected
	 * @var     Zend_Db
	 */
	protected $_db;
	
	//--------------------------------------------------------------------------
	
	/**
	 * Armazena a sessão da aplicação
	 *
	 * @var Zend_Registry
	 */
	protected $_session;

	//--------------------------------------------------------------------------

	/**
	 * Primeiro método iniciado ao acessar o controller
	 *
	 * @name	init
	 * @access	public
	 * @return	void
	 */
	public function init()
	{
		$this->_flashMessenger  = $this->_helper->getHelper('FlashMessenger');
		$this->_cache = Zend_Registry::get('cache');
		
		$this->_db = Zend_Registry::get('db');
		
		$this->_session = Zend_Registry::get('session');
		
		//Caso não tenha efetuado a autenticação
		if( ! ACL::verificarAutenticacao() )
		{
			$this->_session->error = "É necessario efetuar a autenticação no sistema!";
			$this->_redirect('/login/autenticar');
		}
		
		//Caso não tenha permissão de acesso
		if( ! ACL::verificarPermissao( $this->getRequest() ) )
		{
			$this->_session->error = "Você não possui acesso a essa área do sistema!";
			$this->_redirect('/login/autenticar');
		}
		
		parent::init();
	}
	
	//--------------------------------------------------------------------------

	/**
	 * Action padrão de um controller
	 *
	 * @name	indexAction
	 * @access	public
	 * @return	void
	 */
	public function indexAction()
	{
		$this->_redirect($this->_listagem);
	}

	//--------------------------------------------------------------------------

	/**
	 * Action para cadastrar um registro no banco de dados
	 *
	 * @name	cadastrarAction
	 * @access	public
	 * @return	void
	 */
	public function cadastrarAction()
	{
		$this->_form->setAction('cadastrar');
		 
		if( $this->getRequest()->isPost() )
		{
			$post = $this->_request->getParams();

			if( $this->_form->isValid($post) )
			{
				$this->_cadastrar($post);
			}
		}
		 
		$this->view->form = $this->_form;
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Efetua a persistencia do registro no banco de dados
	 * 
	 * @name	_cadastrar
	 * @param 	array $post
	 * @return	void
	 */
	protected function _cadastrar($post)
	{
		$data = $this->_processarDados($post, $this->_model);
		
		$this->_db->beginTransaction();
		
		try 
		{
			$insert = $this->_model->insert($data);
			$mensagem['sucesso'] = 'Registro inserido com sucesso!';
			$this->_db->commit();
		} 
		catch(Exception $e) 
		{
			$this->_db->rollback();
			$insert = false;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
		}
		
		$this->_processar($insert, $mensagem);
	}
	
	//--------------------------------------------------------------------------

	/**
	 * Action para alterar um registro no banco de dados
	 *
	 * @name	alterarAction
	 * @access	public
	 * @return	void
	 */
	public function alterarAction()
	{
		$id = (int) $this->_getParam('id');
		
		$registro = $this->_model->find($id);
		$data = $registro->current();

		if( is_null($data) )  $this->_redirect($this->_listagem);
		
		$this->_form->populate($data->toArray());
		 
		if ( $this->_request->isPost() )
		{
			$post = $this->_request->getParams();
			
			if( $this->_form->isValid($post) )
			{
				$this->_alterar($id, $post);
			}
		}

		$this->view->form = $this->_form;
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Efetua a persistencia do registro no banco de dados
	 * 
	 * @name	_alterar
	 * @param	int			$id		Chave primária
	 * @param 	array 		$post	Dados vindos do formulário
	 * @return	void
	 */
	protected function _alterar($id, $post)
	{
		$data = $this->_processarDados($post, $this->_model);
		$primary = $this->_model->getPrimary();
		
		$where = $this->_model->getAdapter()->quoteInto("{$primary} = ?", $id);
		
		$this->_db->beginTransaction();
		
		try 
		{
			$update = $this->_model->update($data, $where);
			$mensagem['sucesso'] = 'Registro alterado com sucesso!';
			$this->_db->commit();
		} 
		catch(Exception $e) 
		{
			$update = false;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			$this->_db->rollback();
		}
		
		$this->_processar($update, $mensagem);
	}
	
	//--------------------------------------------------------------------------
	
	/**
	 * Action para listar os registros no banco de dados
	 *
	 * @name	listarAction
	 * @access	public
	 * @return	void
	 */
	public function listarAction()
	{
		$this->view->message = $this->_flashMessenger->getMessages();

		if( ! $list = $this->_cache->load($this->_cacheList) )
		{
			$list = $this->_model->fetchAll();
			$this->_cache->save($list, $this->_cacheList);
		}

		$this->view->list = $list;
	}

	//--------------------------------------------------------------------------

	/**
	 * Action para excluir um registro no banco de dados
	 *
	 * @name    excluirAction
	 * @access  public
	 * @return  void
	 */
	public function excluirAction()
	{
		if($this->_hasParam('id') === FALSE) $this->_redirect($this->_listagem);

		$id = (int) $this->_getParam('id');
		$primary = $this->_model->getPrimary();
			
		$where = $this->_model->getAdapter()->quoteInto("{$primary} = ?", $id);
		
		$this->_db->beginTransaction();
		
		try
		{
			$excluir = $this->_model->delete($where);
			$mensagem['sucesso'] = 'Registro excluído com sucesso!';
			$this->_db->commit();
		}
		catch(Exception $e) 
		{
			$excluir = false;
			$mensagem['erro'] = 'Este registro possui dependências e não pode ser excluído!';
			$this->_db->rollback();
		}
		
		$this->_processar($excluir, $mensagem);
	}

	//--------------------------------------------------------------------------

	/**
	 * Processar
	 *
	 * Após efetuar o processamento de uma ação, o retorno comum para inserção,
	 * atualização e exclusão é uma mensagem e um redirecionamento.
	 *
	 * @name    _processar
	 * @access  protected
	 * @param	int|boolean
	 * @return  void
	 */
	protected function _processar($acao, array $mensagem)
	{
		if($acao)
		{
			$this->_flashMessenger->addMessage($mensagem['sucesso']);
			$this->_cache->remove($this->_cacheList);
		}
		else
		{
			$this->_flashMessenger->addMessage($mensagem['erro']);
		}

		$this->_redirect($this->_listagem);
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Obtém os dados para inserção no banco de dados em forma de array.
	 *
	 * @name	_processarDados
	 * @access	protected
	 * @param	array	$post - array vindo de uma requisição POST
	 * @param	object	$model - Objeto que herda de Zend_Db_Table
	 * @return 	array
	 */
    protected function _processarDados(array $post, $model)
    {
    	if(empty($post) || ! is_object($model)) return NULL;
    	
    	$cols = $model->getCols();
    	$data = array();
    	
    	foreach($cols as $value)
    	{
			if( array_key_exists($value, $post) )
			{
				$data[$value] = $post[$value];
			}
		}
    
		//A chave primária deve ser removida para a persistencia dos dados
		$primary = $model->getPrimary();
		
		if(is_array($primary))
		{
			foreach($primary as $p)
			{
				unset($data[$p]);
			}
		}
		else
		{
			unset($data[$primary]);
		}
			
    	return $data;
    }
    
    //--------------------------------------------------------------------------
}