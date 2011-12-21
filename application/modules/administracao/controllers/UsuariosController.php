<?php
/**
 * Administracao_UsuariosController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Administracao_UsuariosController extends Controller
{
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
		$this->_model = new Application_Model_Usuarios();
		$this->_form = new Administracao_Form_Usuarios();
		
		$this->_cacheList = 'usuarios';
		$this->_listagem = '/administracao/usuarios/listar';
		
		parent::init();
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
				$post['senha'] = md5($post['senha']);
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

		$this->_form->formAlterar($data);

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
     * Action para alterar a senha de um usuário
     *
     * @name    alterarActionSenha
     * @access  public
     * @return  void
     */
    public function alterarSenhaAction()
    {
        $id = (int) $this->_getParam('id');

        $registro = $this->_model->find($id);
        $data = $registro->current();
        
        if( is_null($data) )  $this->_redirect($this->_listagem);
         
        $this->_form->formAlterarSenha($data);
         
        if ( $this->_request->isPost() )
        {
            $post = $this->_request->getParams();

            if( $this->_form->isValid($post) )
            {
	            $post['senha'] = md5($post['senha']);
            	$this->_alterar($id, $post);
            }
        }

        $this->view->form = $this->_form;
    }

    //--------------------------------------------------------------------------
    
    public function inativarAction()
    {
	    $id = (int) $this->_request->getParam('id');
	    				
	    $admin = $this->_model->isAdmin($id);
	    
	    if($admin !== null)
	    {
		    $mensagem = 'Usuário administrador não pode ser inativado!';
		    						
		    $this->_flashMessenger->addMessage($mensagem);
		    
		    $this->_redirect($this->_listagem);
	    }
	    
	    $post = array('situacao' => Application_Model_Usuarios::SITUACAO_INATIVO);
	    
	    $this->_alterar($id, $post);
    }
    
    //--------------------------------------------------------------------------
    
}