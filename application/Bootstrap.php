<?php

/**
 * Bootstrap
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Inicia as configurações de Banco de Dados
	 *
	 * @name	_initDB
	 * @access	protected
	 * @return 	void
	 */
	protected function _initDB()
	{	
		$dbConfig = new Zend_Config_Ini(
			APPLICATION_PATH . '/configs/application.ini',
			APPLICATION_ENV
		);
		
		$db = Zend_Db::factory(
			$dbConfig->resources->db->adapter,
			$dbConfig->resources->db->params->toArray()
		);
		
		Zend_Db_Table_Abstract::setDefaultAdapter($db);
		
		Zend_Registry::set('db', $db);
	}
	
	//--------------------------------------------------------------------------
	
	/**
	 * Inicia as configurações de Banco de Dados para visualizar com o FireBug
	 *
	 * @name	_initDB
	 * @access	protected
	 * @return 	void
	 */
	protected function _initDbProfiler()
    {
		$this->bootstrap('db');
            
		$profiler = new Zend_Db_Profiler_Firebug('Visualização');
		$profiler->setEnabled(true);
            
		Zend_Registry::get('db')->setProfiler($profiler);
    }
	
	//--------------------------------------------------------------------------
	
    /**
	 * Inicia o autoloader do Zend Framework
	 *
	 * @name	_initAutoload
	 * @access	protected
	 * @return 	void
	 */
	protected function _initAutoload()
	{
		$loader = Zend_Loader_Autoloader::getInstance();
		$loader->setFallbackAutoloader(true);
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Faz com que o charset padrão da aplicação seja UTF-8
	 *
	 * @name	_initCharset
	 * @access	protected
	 * @return	void
	 */
	protected function _initCharset()
	{
		header('Content-type: text/html; charset=UTF-8');
	}

	//--------------------------------------------------------------------------
	
	/**
	 * Inicia o cache do banco de dados
	 *
	 * @name	_initDbCache
	 * @access	protected
	 * @return	void
	 */
	protected function _initCache()
	{
		$frontendOptions = array('automatic_serialization' => true);
		$backendOptions = array('cache_dir' => APPLICATION_PATH.'/cache');

		$cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

		//Salva o cache no Registry para ser usado posteriormente
		Zend_Registry::set('cache', $cache);
		
		//Cache de metadados das tabelas
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
	}
	
	//--------------------------------------------------------------------------
	
	/**
	 * Inicia todas as regras de acesso ao sistema de acordo com o perfil do 
	 * usuário.
	 * 
	 * @name	_initAcl
	 * @access	protected
	 * @return	void
	 */
	
	protected function _initAcl()
	{
		$acl = ACL::initAcl();
		
		Zend_Registry::set('acl', $acl);
	}
	
	
	//--------------------------------------------------------------------------
	
	/**
     * Inicia a sessão do banco de dados
     *
     * @name    _initSession
     * @access  protected
     * @return  void
     */
	protected function _initSession()
	{
		Zend_Session::start();
		
		Zend_Registry::set('session', new Zend_Session_Namespace());
	}
	
    //--------------------------------------------------------------------------
    
	/**
     * Inicia o menu da aplicação
     *
     * @name    _initNavigation
     * @access  protected
     * @return  void
     */
	protected function _initNavigation()
	{
		$this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();		
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/navigation.xml', 'nav');

		$container = new Zend_Navigation($config);
		
		$view->navigation($container);
	}
	
	//--------------------------------------------------------------------------
	
	/**
	 * Inicia o local da aplicação (muito utilizado para trabalhar com datas)
	 * 
	 * @name	_initLocale
	 * @access	protected
	 * @return	void
	 */
	protected function _initLocale()
	{
		$locale = new Zend_Locale('pt_BR');
		
		Zend_Registry::set('locale', $locale);
	}
	
	//--------------------------------------------------------------------------

}

