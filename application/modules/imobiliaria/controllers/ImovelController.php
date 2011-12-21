<?php
/**
 * Imobiliaria_ImovelController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Imobiliaria_ImovelController extends Controller
{
	//--------------------------------------------------------------------------

	protected $_modelFoto;

	protected $_modelEndereco;

	protected $_pesquisaImovel;

	//--------------------------------------------------------------------------

	/**
	 * Guarda o valor do cache de list para ser manipulado.
	 *
	 * @var string
	 */
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
		$this->_model = new Application_Model_Imovel();

		$this->_modelFoto = new Application_Model_FotoImovel();
		$this->_modelEndereco = new Application_Model_Endereco();

		$this->_form = new Imobiliaria_Form_Imovel();
		$this->_pesquisaImovel = new Imobiliaria_Form_PesquisaImovel();

		$this->_cacheList = 'imovel';
		$this->_cachePesquisaImovel = 'pesquisa_imovel';
		
		$this->_listagem = '/imobiliaria/imovel/listar';

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

		// Trata Imovel
		$imovel = $this->_model->find($id);
		$resultImovel = $imovel->current();

		// Trata foto
		$resultFoto = $this->_modelFoto->buscar($id);
		$resultFoto = array_shift($resultFoto);

		// Trata Endereco
		$idEndereco = $resultImovel['endereco_id'];
		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$resultEndereco = $endereco->current();

		if( is_null($resultImovel) || is_null($resultFoto) || is_null($resultEndereco) )  $this->_redirect($this->_listagem);

		$this->_form->populate($resultImovel->toArray());
		//$this->_form->populate($resultFoto->toArray());
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
			$updateImovel = $this->_model->update($data, $where);
			$updateEndereco = $this->_modelEndereco->atualizar($post);
			//$updateFoto = $this->_modelFoto->atualizar($post);

			$mensagem['sucesso'] = 'Registro alterado com sucesso!';
			$this->_db->commit();
		}
		catch(Exception $e)
		{
			$updateImovel		= FALSE;
			$updateEndereco 	= FALSE;
			//$updateFoto			= FALSE;

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

		$list = $this->_model->listar(NULL, NULL, Application_Model_Imovel::SITUACAO_ATIVO);

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

	// Visualizar o cadastro do imoveis completo selecionado na listagem.
	// @TODO
	public function visualizarAction()
	{
		$id = array('id_imovel = ?' => (int) $this->_getParam('id'));

		$list = $this->_model->listar($id);

		$this->view->list = $list;
	}

	//--------------------------------------------------------------------------

	// Visualizar o cadastro de cliente excluidos.
	// @TODO
	public function backListAction()
	{
		$where = array('situacao = ?' => Application_Model_Imovel::SITUACAO_INATIVO);
		$list = $this->_model->listar($where);

		$this->view->backList = $list;
	}

	//--------------------------------------------------------------------------

	public function inativarAction()
	{
		$id = (int) $this->_request->getParam('id');

		$post = array('situacao' => Application_Model_Imovel::SITUACAO_INATIVO);

		$this->_alterar($id, $post);
	}

	//--------------------------------------------------------------------------

	public function pesquisarAction()
	{
		$this->_pesquisaImovel->setAction('pesquisar');
			
		if( $this->getRequest()->isPost() )
		{
			$post = $this->_request->getParams();

			if( $this->_pesquisaImovel->isValid($post) )
			{
				$result = $this->_model->pesquisaDetalhada($post);
				$this->_cache->save($result, $this->_cachePesquisaImovel);
				$this->_redirect('/imobiliaria/imovel/resultado-pesquisa');
			}
		}
			
		$this->view->form = $this->_pesquisaImovel;

	}

	//--------------------------------------------------------------------------

	public function resultadoPesquisaAction()
	{
		$pesquisa = $this->_cache->load($this->_cachePesquisaImovel);

		$this->view->pesquisaImovel = $pesquisa;
	}

	//--------------------------------------------------------------------------

}


