<?php
/**
 * Biblioteca para controle de acesso na aplicação
 *
 * INSTALAÇÃO: Basta jogar dentro da pasta library do seu projeto em Zend Framework.
 *
 * @package library
 * @name    ACL
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 */
class ACL
{
	/**
	 * Armazena o recusto do módulo Default referente ao controller Principal
	 * 
	 * @var string
	 */
	const DEFAULT_PRINCIPAL = 'default_principal';
	
	//--------------------------------------------------------------------------
	
	/**
	 * Armazena o recusto do módulo Default referente ao controller 
	 * administracao_usuarios e administracao_imobiliaria.
	 * 
	 * @var string
	 */
	const ADMINISTRACAO_USUARIOS	= 'administracao_usuarios';
	const ADMINISTRACAO_IMOBILIARIA	= 'administracao_imobiliaria';
	
	//--------------------------------------------------------------------------
	
	/**
	 * Armazena o recusto do módulo Default referente ao controller 
	 * imobiliaria_clientes, imobiliaria_corretor e imobiliaria_imovel.
	 * 
	 * @var string
	 */
	const IMOBILIARIA_CLIENTES	= 'imobiliaria_clientes';
	const IMOBILIARIA_CORRETOR	= 'imobiliaria_corretor';
	const IMOBILIARIA_IMOVEL	= 'imobiliaria_imovel';
	
	//--------------------------------------------------------------------------
	
	/**
	 * Inicia as configurações de Acl atribuindo as regras e recursos do sistema
	 *
	 * @name   initAcl
	 * @access static
	 * @return Zend_Acl
	 */
	public static function initAcl()
	{
		$acl = new Zend_Acl;
		
		self::adicionarRecursos($acl);

		self::perfilUsuario($acl);
		self::perfilAdministrador($acl);

		return $acl;
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Adiciona os recursos que serão controlados
	 *
	 * @name    adicionarRecursos
	 * @access  private static
	 * @param   Zend_Acl $acl
	 * @return  void
	 */
	private static function adicionarRecursos(Zend_Acl $acl)
	{
		//Recursos do módulo Default
		$acl->addResource(new Zend_Acl_Resource(self::DEFAULT_PRINCIPAL));
		
		//Recursos do módulo Administração
		$acl->addResource(new Zend_Acl_Resource(self::ADMINISTRACAO_USUARIOS));
		$acl->addResource(new Zend_Acl_Resource(self::ADMINISTRACAO_IMOBILIARIA));
		
		//Recursos do módulo Consultório
		$acl->addResource(new Zend_Acl_Resource(self::IMOBILIARIA_CLIENTES));
		$acl->addResource(new Zend_Acl_Resource(self::IMOBILIARIA_CORRETOR));
		$acl->addResource(new Zend_Acl_Resource(self::IMOBILIARIA_IMOVEL));
		
		return $acl;
	}
	
	//--------------------------------------------------------------------------

	/**
	 * Inicia as permissões do usuário do consultório
	 *
	 * @name    perfilUsuario
	 * @access  private static
	 * @param   Zend_Acl $acl
	 * @return  void
	 */
	private static function perfilUsuario(Zend_Acl $acl)
	{
		$acl->addRole(
			new Zend_Acl_Role(Application_Model_Usuarios::PERFIL_USUARIO)
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_USUARIO,
           	self::DEFAULT_PRINCIPAL,
			array('index')
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_USUARIO,
           	self::IMOBILIARIA_CLIENTES,
			array('cadastrar', 'alterar', 'excluir', 'listar', 'visualizar', 'inativar', 'interesse', 'alterar-interesse')
		);
		 
		$acl->allow(
			Application_Model_Usuarios::PERFIL_USUARIO,
            self::IMOBILIARIA_CORRETOR,
			array('cadastrar', 'listar', 'visualizar', 'inativar')
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_USUARIO,
            self::IMOBILIARIA_IMOVEL,
			array('cadastrar', 'alterar', 'listar', 'visualizar', 'inativar', 'pesquisar', 'resultado-pesquisa', 'visualizar-resultado')
		);
		
		return $acl;
	}

	//--------------------------------------------------------------------------

	/**
	 * Inicia as permissões do usuário Administrador
	 * 
	 * Administrador do sistema. Pode cadastrar consultórios, e usuários
	 * administradores, porém não pode excluir nenhum usuário administrador.
	 *
	 * O usuário Administrador herda todas as permissões do usuário Usuário.
	 *
	 * @name    perfilAdministrador
	 * @access  private static
	 * @param   Zend_Acl $acl
	 * @return  void
	 */
	private static function perfilAdministrador(Zend_Acl $acl)
	{
		
		$acl->addRole(
			new Zend_Acl_Role(Application_Model_Usuarios::PERFIL_ADMINISTRADOR)
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
           	self::DEFAULT_PRINCIPAL,
			array('index')
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
           	self::IMOBILIARIA_CLIENTES,
			array('cadastrar', 'alterar', 'listar', 'excluir', 'visualizar', 'inativar', 'backlist', 'interesse', 'alterar-interesse', 'resultado-pesquisa')
		);
		 
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
            self::IMOBILIARIA_CORRETOR,
			array('cadastrar', 'alterar', 'listar', 'excluir', 'visualizar', 'inativar', 'backlist')
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
            self::IMOBILIARIA_IMOVEL,
			array('cadastrar', 'alterar', 'listar', 'excluir', 'visualizar', 'inativar', 'backlist', 'pesquisar', 'resultado-pesquisa', 'visualizar-resultado')
		);
		
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
			self::ADMINISTRACAO_IMOBILIARIA,
			array('cadastrar', 'alterar', 'listar', 'excluir', 'visualizar', 'inativar', 'backlist')
		);
		 
		$acl->allow(
			Application_Model_Usuarios::PERFIL_ADMINISTRADOR,
			self::ADMINISTRACAO_USUARIOS,
			array('cadastrar', 'alterar', 'alterar-senha', 'listar', 'excluir', 'visualizar', 'inativar', 'backlist')
		);

	}

	//--------------------------------------------------------------------------


	/**
	 * Verifica se o usuario que está tentando acessar um recurso está autenticado
	 *
	 * @name   verificarAutenticacao
	 * @access static
	 * @return boolean
	 */
	public static function verificarAutenticacao()
	{
		$auth = Zend_Auth::getInstance();

		return $auth->hasIdentity();
	}

	//--------------------------------------------------------------------------

	/**
	 * Verifica se o usuario que está tentando acessar um recurso possui a devida
	 * permissão de acesso.
	 *
	 * @name    verificarPermissao
	 * @param   Zend_Controller_Action
	 * @access  static
	 * @return  boolean
	 */
	public static function verificarPermissao($request)
	{
		$session = Zend_Registry::get('session');
		
		//Obtem o nome do modulo, do controlador e da action requisitada
		$module      = $request->getModuleName();
		$controller  = $request->getControllerName();
		$action      = $request->getActionName();

		$resource = "{$module}_{$controller}";
		$privilege = $action;
		 
		$auth = Zend_Auth::getInstance();

		$perfil = null;
		//Se estiver logado pega o perfil para verificar a permissão
		if( $auth->hasIdentity() ) $perfil = $session->usuario->perfil;
			
		$acl = Zend_Registry::get('acl');

		return $acl->isAllowed($perfil, $resource, $privilege);
	}

	//--------------------------------------------------------------------------
}