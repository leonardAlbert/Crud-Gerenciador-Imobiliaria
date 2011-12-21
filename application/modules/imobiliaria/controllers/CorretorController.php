<?php
/**
 * Imobiliaria_CorretorController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_CorretorController extends Controller
{
	
	protected $_modelTelefone;
	protected $_modelEndereco;
	
   	/**
	 * Primeiro método iniciado ao acessar o controller
	 *
	 * @name	init
	 * @access	public
	 * @return	void
	 */
	public function init()
	{
		$this->_model = new Application_Model_Corretor();
		$this->_modelTelefone = new Application_Model_TelefoneCorretores();
		$this->_modelEndereco = new Application_Model_Endereco();
		
		$this->_form = new Imobiliaria_Form_Corretor();
		
		$this->_cacheList = 'corretor';
		$this->_listagem = '/imobiliaria/corretor/listar';
		
		parent::init();
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
		
		$list = $this->_model->listar(NULL, NULL, Application_Model_Corretor::SITUACAO_ATIVO);
		
		$this->view->list = $list;
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
		$this->_db->beginTransaction();
		
		try 
		{
			$insert = $this->_model->cadastrar($post);
			
			$mensagem['sucesso'] = 'Registro inserido com sucesso!';
			$this->_db->commit();
		} 
		catch(Exception $e) 
		{
			$this->_db->rollback();
			$insert = FALSE;
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
		
		// Trata corretor
		$corretor = $this->_model->find($id);
		$resultCorretor = $corretor->current();
		
		// Trata telefone
		$resultTelefone = $this->_modelTelefone->buscar($id);
		$resultTelefone = array_shift($resultTelefone);
		
		// Trata Endereco
		$idEndereco = $resultCorretor['endereco_id'];
		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$resultEndereco = $endereco->current();
		
		
		if( is_null($resultCorretor) || is_null($resultTelefone) || is_null($resultEndereco) )  $this->_redirect($this->_listagem);
		
		$this->_form->populate($resultCorretor->toArray());
		$this->_form->populate($resultTelefone->toArray());
		$this->_form->populate($resultEndereco->toArray());
		 
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
			$updateCorretor = $this->_model->update($data, $where);
			$updateEndereco = $this->_modelEndereco->atualizar($post);
			$updateTelefone = $this->_modelTelefone->atualizar($post);
			
			$mensagem['sucesso'] = 'Registro alterado com sucesso!';
			$this->_db->commit();
		} 
		catch(Exception $e) 
		{
			$updateCorretor = false;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			$this->_db->rollback();
		}
		
		$this->_processar($updateCorretor, $mensagem);
	}
	
	//--------------------------------------------------------------------------
	
	// Visualizar o cadastro do corretor completo selecionado selecionado na listagem.
	// @TODO
	public function visualizarAction()
	{
		$id = array('id_corretor = ?' => (int) $this->_getParam('id'));
		$list = $this->_model->listar($id);

		$this->view->list = $list;
	}
	
	//--------------------------------------------------------------------------
	
		
	// Visualizar o cadastro de corretore excluidos.
	// @TODO
	public function backListAction()
	{
		$where = array('situacao = ?' => Application_Model_Clientes::SITUACAO_INATIVO);
		$list = $this->_model->listar($where);

		$this->view->backList = $list;
	}
	
	//--------------------------------------------------------------------------

	public function inativarAction()
	{
		$id = (int) $this->_request->getParam('id');

		$post = array('situacao' => Application_Model_Clientes::SITUACAO_INATIVO);

		$this->_alterar($id, $post);
	}

	//--------------------------------------------------------------------------1
	
	
	//--------------------------------------------------------------------------
	
	
	
}