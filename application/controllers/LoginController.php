<?php
/**
 * LoginController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class LoginController extends Zend_Controller_Action
{
	/**
	 * Formulário responsável por essa entidade
	 *
	 * @access  private
	 * @var     Administracao_Form_Login
	 */
	private $_form;

	//--------------------------------------------------------------------------

	/**
	 * Model responsável por essa entidade
	 *
	 * @access  private
	 * @var     Application_Model_Usuario
	 */
	private $_model;

	//--------------------------------------------------------------------------

	/**
	 * Objeto Flash Messenger
	 *
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger = NULL;

	//--------------------------------------------------------------------------
	
	/**
	 * Redirecionamento para o controller Principal
	 *
	 * @var string
	 */
	const REDIRECT = '/principal';
	
	//--------------------------------------------------------------------------
   
    /**
     * Armazena a sessão do Zend Framework
     *
     * @var Zend_Registry
     */
    private $_session;
    
    //--------------------------------------------------------------------------
     
    /**
     * Armazena o objeto Zend_Auth
     *
     * @var Zend_Auth
     */
    private $_auth;
    
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
		parent::init();
		
		$this->_model = new Application_Model_Usuarios();
		$this->_form = new Application_Form_Login();
		
		$this->_session = Zend_Registry::get('session');
		$this->_auth = Zend_Auth::getInstance();
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
		$this->_redirect('/login/autenticar');
	}

	//--------------------------------------------------------------------------
	
	public function autenticarAction()
	{
		//Se estiver logado, é redirecionado para a página principal
		if( ACL::verificarAutenticacao() ) $this->_redirect(self::REDIRECT);
		
		$this->_form->setAction('/login/autenticar');
		
		if( $this->_request->isPost() )
        {
            $dados = $this->_request->getPost();
            if( $this->_form->isValid($dados) ) 
            {
            	$authAdapter = new Zend_Auth_Adapter_DbTable(
                    null,
                    'usuarios',
                    'login',
                    'senha',
                    'MD5(?) AND situacao = ' . Application_Model_Usuarios::SITUACAO_ATIVO
                );
                
                $this->_autenticar($authAdapter, $dados, self::REDIRECT);
            }
            else 
            {
                $this->_form->populate($dados);
            }
        }
        
		$this->view->message = $this->_session->error;
        
        $this->view->form = $this->_form;
	}

	//--------------------------------------------------------------------------

	/**
	 * Função utilizada para autenticação de usuário, seja um usuário comum ou
	 * um administrador
	 * 
	 * @name   _autenticar
	 * @param  Zend_Auth_Adapter_DbTable $authAdapter
	 * @param  array
	 * @param  string
	 * @return void
	 */
	private function _autenticar($authAdapter, $dados, $redirect)
	{
		//configura o usuário/senha
		$authAdapter->setIdentity($dados['login'])->setCredential($dados['senha']);
		  
		//tenta fazer a autenticação
		$result = $this->_auth->authenticate($authAdapter);
		
		switch( $result->getCode() ) 
		{
			case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
				$this->_session->error = 'Usuário inexistente';
				$this->_form->populate($dados);
			break;

			case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
				$this->_session->error = 'Senha inválida';
				$this->_form->populate($dados);
			break;

			case Zend_Auth_Result::SUCCESS:
				$usuario = $authAdapter->getResultRowObject();

				//Coloca o usuário na sessão
                $this->_session->usuario = $usuario;
                
                //Redireciona para o controller Principal
				$this->_redirect(self::REDIRECT);
				
			break;
		}
	}
	
	//--------------------------------------------------------------------------
	
    /**
     * Action utilizada para efetuar logout do sistem
     * 
     * @name	sairAction
     * @access	public
     * @return	void
     */
    public function sairAction()
    {
    	$this->_auth->clearIdentity();

    	//Remove os dados do usuário da sessão
    	unset($this->_session->usuario);
    	
    	$this->_redirect('/login/autenticar');
    }
    
    //--------------------------------------------------------------------------
	
}