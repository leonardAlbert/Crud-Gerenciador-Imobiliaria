<?php
/**
 * PrincipalController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class PrincipalController extends Zend_Controller_Action
{
	/**
	 * Objeto Flash Messenger
	 *
	 * @var Zend_Controller_Action_Helper_FlashMessenger
	 */
	protected $_flashMessenger = NULL;

	//--------------------------------------------------------------------------

	/**
	 * Armazena a sessão da aplicação
	 *
	 * @var Zend_Registry
	 */
	private $_session;
	
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
        
        $this->_flashMessenger  = $this->_helper->getHelper('FlashMessenger');
		$this->_session = Zend_Registry::get('session');
    	
		//Caso não tenha efetuado a autenticação
		if( ! ACL::verificarAutenticacao() )
		{
			$this->_session->error = "É necessario efetuar a autenticação no sistema!";
			$this->_redirect('/login/autenticar');
		}
		
		//Caso não tenha permissão de acesso
		if( ! ACL::verificarPermissao($this->getRequest() ) )
		{
			$this->_session->error = "Você não possui acesso a essa área do sistema!";
			$this->_redirect('/login/autenticar');
		}
		
    }
    
    //--------------------------------------------------------------------------
    
    public function indexAction()
    {
    	$usuario = $this->_session->usuario->nome;
    	
    	$this->view->boasVindas  = "Usuário logado: {$usuario}!";
    	$this->view->message = $this->_session->error;
    }
    
    //--------------------------------------------------------------------------

}