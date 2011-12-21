<?php
/**
 * Application_Model_Usuarios
 * 
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Usuarios extends Zend_Db_Table
{
	/**
	 * Constantes utilizadas para identificar a situação deste usuário
	 *  
	 * @var int
	 */
	const SITUACAO_ATIVO = 1;
	const SITUACAO_INATIVO = 2;
	
	//--------------------------------------------------------------------------
	
	/**
     * Administrador do sistema. Pode alterar os dados de seu consultório, 
     * cadastra, alterar e excluir usuários do sistema.
     * 
     * @var int
     */
	const PERFIL_ADMINISTRADOR = 1;
	
	//--------------------------------------------------------------------------
	
    /**
     * Um simples usuário do sistema, será utilizado apenas no módulo Consultório.
     * 
     * @var int
     */
	const PERFIL_USUARIO = 2;
	
	//--------------------------------------------------------------------------

    protected $_name = 'usuarios';
	
    //--------------------------------------------------------------------------
    
	protected $_primary = 'id_usuario';
	
	//--------------------------------------------------------------------------
	
	protected $_cols = array(
		'id_usuario',
		'nome',
		'login',
        'senha',
        'situacao',
        'perfil'
	);
	
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
	
	/**
	 * Retorna o perfil do usuário
	 * 
	 * @param  int     $perfil
	 * @return string
	 */
	public static function getPerfil($perfil)
	{
		switch ($perfil) 
		{
			case self::PERFIL_ADMINISTRADOR:
                return 'Administrador';
            break;
            
            case self::PERFIL_USUARIO:
                return 'Usuário';
            break;
		}
	}
	
	//--------------------------------------------------------------------------
	
	/**
     * Retorna a situação do usuário
     * 
     * @param  int     $situacao
     * @return string
     */
    public static function getSituacao($situacao)
    {
        switch ($situacao) 
        {
            case self::SITUACAO_ATIVO:
                return 'Ativo';
            break;
            
            case self::SITUACAO_INATIVO:
                return 'Inativo';
            break;
        }
    }
    
    //--------------------------------------------------------------------------
    
    /**
    * Retorna se o ID do usuário informado é administrador do sistema, pois ele
     * não pode ser alterado, inativado ou excluido.
    *
    * @param	int 	$id
    */
    public function isAdmin($id)
    {
	    $select = $this->select()
				    ->from($this->_name)
			    	->where('login = ?', 'admin')
					->where('id_usuario = ?', $id);
        					
    	return $this->fetchAll($select)->current();
       }
        
    //--------------------------------------------------------------------------



}
