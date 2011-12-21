<?php
/**
 * Imobiliaria_ClientesController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_ClientesController extends Controller
{
	protected $_modelTelefone;
	protected $_modelEndereco;

	//--------------------------------------------------------------------------

	protected $_formInteresse;

	//--------------------------------------------------------------------------
	
	protected $_modelInteresse;
	
	//--------------------------------------------------------------------------
	
	protected $_modelImovel;
	
	//--------------------------------------------------------------------------
	
	protected $_cachePesquisaImovel;
	
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
		$this->_model = new Application_Model_Clientes();
		$this->_modelTelefone = new Application_Model_TelefoneClientes();
		$this->_modelEndereco = new Application_Model_Endereco();
		$this->_modelInteresse = new Application_Model_InteresseCliente();
		$this->_modelImovel = new Application_Model_Imovel();
		
		
		$this->_form = new Imobiliaria_Form_Clientes();
		$this->_formInteresse = new Imobiliaria_Form_InteresseCliente();

		$this->_cacheList = 'clientes';
		$this->_cachePesquisaImovel = 'pesquisa_imovel';
		$this->_listagem = '/imobiliaria/clientes/listar';

		parent::init();
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

		// Trata cliente
		$cliente = $this->_model->find($id);
		$resultCliente = $cliente->current();

		// Trata telefone
		$resultTelefone = $this->_modelTelefone->buscar($id);
		$resultTelefone = array_shift($resultTelefone);

		// Trata Endereco
		$idEndereco = $resultCliente['endereco_id'];
		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$resultEndereco = $endereco->current();
			
		if( is_null($resultCliente) || is_null($resultTelefone) || is_null($resultEndereco) )  $this->_redirect($this->_listagem);

		$this->_form->populate($resultCliente->toArray());
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
			$updateCliente	= $this->_model->update($data, $where);
			$updateEndereco	= $this->_modelEndereco->atualizar($post);
			$updateTelefone = $this->_modelTelefone->atualizar($post);

			$mensagem['sucesso'] = 'Registro alterado com sucesso!';
			$this->_db->commit();
		}
		catch(Exception $e)
		{
			$updateCliente = false;
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
			$this->_db->rollback();
		}

		$this->_processar($updateCliente, $mensagem);
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

		$list = $this->_model->listar(NULL, NULL, Application_Model_Clientes::SITUACAO_ATIVO);

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
			$insert = FALSE;

			$this->_db->rollback();
			$mensagem['erro'] = "Ocorreu o seguinte erro: {$e->getMessage()}";
		}

		$this->_processar($insert, $mensagem);
	}

	//--------------------------------------------------------------------------

	// Visualizar o cadastro do cliente completo selecionado selecionado na listagem.
	// @TODO
	public function visualizarAction()
	{
		$id = array('id_cliente = ?' => (int) $this->_getParam('id'));
		$list = $this->_model->listar($id);

		if(! is_null($list) || ! empty($list))
		{
			foreach ($list as $row)
			{
				$interesseId = $row->getCliente()->interesse_id;
			}
		
			$where = array('id_interesse = ?' => (int) $interesseId);
			$interesse = $this->_modelInteresse->listar($where);
				
			if($interesse != NULL || ! empty($interesse))
			{
				$interesse = $interesse->toArray();
		
				// Visualizar imoveis disponiveis pelo interesse do cliente.
				$imoveis = $this->_modelImovel->pesquisaDetalhada(current($interesse));
				if (count($imoveis) != 0)
				{
					$this->view->totalImovel = count($imoveis);
					$this->_cache->save($imoveis, $this->_cachePesquisaImovel);
				}
		
				$this->view->interesse = $interesse;
			}
		}

		$this->view->list = $list;
	}

	//--------------------------------------------------------------------------


	// Visualizar o cadastro de cliente excluidos.
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

	//--------------------------------------------------------------------------

	/*
	 public function inativarAction()
	{
	$id = (int) $this->_request->getParam('id');

	$post = array('situacao' => Application_Model_Clientes::SITUACAO_INATIVO);

	$this->_alterar($id, $post);
	}

	//--------------------------------------------------------------------------

	public function ativarAction()
	{
	$id = (int) $this->_request->getParam('id');

	$post = array('situacao' => Application_Model_Clientes::SITUACAO_ATIVO);

	$this->_alterar($id, $post);
	}

	*/

	//--------------------------------------------------------------------------

	/**
	 * Action para cadastrar um registro no banco de dados
	 *
	 * @name	cadastrarAction
	 * @access	public
	 * @return	void
	 */
	public function interesseAction()
	{
		$id = (int) $this->_getParam('id');

		if ( $this->_request->isPost() )
		{
			$post = $this->_request->getParams();

			if( $this->_formInteresse->isValid($post) )
			{
				$this->_cadastrarInteresse($id, $post);
			}
		}

		$this->view->form = $this->_formInteresse;
	}

	//--------------------------------------------------------------------------

	/**
	 * Efetua a persistencia do registro no banco de dados
	 *
	 * @name	_cadastrar
	 * @param 	array $post
	 * @return	void
	 */
	protected function _cadastrarInteresse($id, $post)
	{
		$this->_db->beginTransaction();

		$primary = $this->_model->getPrimary();
		$whereModelCliente = $this->_model->getAdapter()->quoteInto("{$primary} = ?", $id);

		try
		{
			$insertInteresse = $this->_modelInteresse->cadastrar($post);

			$postModelCliente = array('interesse_id' => (int) $insertInteresse);
			$insertModelCliente = $this->_model->update($postModelCliente, $whereModelCliente);

			
			$mensagem['sucesso'] = 'Registro inserido com sucesso!';
			$this->_db->commit();
		}
		catch(Exception $e)
		{
			$this->_db->rollback();
			$interesse = FALSE;
			$postModelCliente = FALSE;
			$insertModelCliente = FALSE;

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
	public function alterarInteresseAction()
	{
		$id = (int) $this->_getParam('id');

		$interesse = $this->_modelInteresse->find($id);
		$interesse = $interesse->current();

		$this->_formInteresse->populate($interesse->toArray());

		if ( $this->_request->isPost() )
		{
			$post = $this->_request->getParams();

			if( $this->_formInteresse->isValid($post) )
			{
				$this->_alterarInteresse($id, $post);
			}
		}

		$this->view->form = $this->_formInteresse;
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
	protected function _alterarInteresse($id, $post)
	{
		$data = $this->_processarDados($post, $this->_modelInteresse);
		$primary = $this->_modelInteresse->getPrimary();

		$where = $this->_modelInteresse->getAdapter()->quoteInto("{$primary} = ?", $id);

		$this->_db->beginTransaction();
		
		try
		{
			$update	= $this->_modelInteresse->update($data, $where);

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
}