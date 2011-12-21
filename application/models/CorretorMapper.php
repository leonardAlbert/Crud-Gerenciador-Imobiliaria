<?php
/**
 * CorretorMapper
 * 
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class CorretorMapper
{
	
	protected $_modelCorretor;
	protected $_modelTelefone;
	protected $_modelEndereco;
	
	//--------------------------------------------------------------------------
	
	protected $_corretor;
	protected $_telefone;
	protected $_endereco;
    
	//--------------------------------------------------------------------------
	
	public function __construct(Zend_Db_Table_Row $corretor)
	{
		$this->_modelCorretor = new Application_Model_Corretor();
		$this->_modelTelefone = new Application_Model_TelefoneCorretores();
		$this->_modelEndereco = new Application_Model_Endereco();
		
		return $this->_mapper($corretor);
	}
	
	//--------------------------------------------------------------------------
	
	protected function _mapper(Zend_Db_Table_Row $corretor)
	{
		$this->_corretor = $corretor;
		$id = $corretor->id_corretor;
		$idEndereco = $corretor->endereco_id;
		
		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$telefone = $this->_modelTelefone->buscar($id);
		
		foreach ($telefone as $row) 
		{ 
			$arrTelefone = $row; 
		}
		$this->_telefone = $arrTelefone;
		
		$arrEndereco = array();
		foreach ($endereco as $row) 
		{ 
			$arrEndereco = $row; 
		}
		$this->_endereco = $arrEndereco;
		
		return $this;
	}
	
	//--------------------------------------------------------------------------
	
	public function getCorretor()
	{
		return $this->_corretor;
	}
	
	//--------------------------------------------------------------------------
	
	public function getTelefone()
	{
		return $this->_telefone;
	}
	
	//--------------------------------------------------------------------------
		
	public function getEndereco()
	{
		return $this->_endereco;
	}
	
	//--------------------------------------------------------------------------
	
	public function setCorretor($corretor)
	{
		$this->_corretor = $corretor;
	}
	
	//--------------------------------------------------------------------------
	
	public function setTelefone($telefone)
	{
		$this->_telefone = $telefone;
	}
  
	//--------------------------------------------------------------------------
		
	public function setEndereco($endereco)
	{
		$this->_endereco = $endereco;
	}
  
	//--------------------------------------------------------------------------
	
	/**
     * Retorna a situação do usuário
     * 
     * @param  int     $situacao
     * @return string	
     */
    public function getSituacao()
    {
        switch ($this->getCorretor()->situacao) 
        {
            case Application_Model_Corretor::SITUACAO_ATIVO:
                return 'Ativo';
            break;
            
            case Application_Model_Corretor::SITUACAO_INATIVO:
                return 'Inativo';
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
    public function getSexo()
    {
        switch ($this->getCorretor()->sexo) 
        {
            case Application_Model_Corretor::SEXO_MASCULINO:
                return 'Masculino';
            break;
            
            case Application_Model_Corretor::SEXO_FEMININO:
                return 'Feminino';
            break;
        }
    }
    
    //--------------------------------------------------------------------------
    
    
}