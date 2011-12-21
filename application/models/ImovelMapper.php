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
class ImovelMapper
{
	//--------------------------------------------------------------------------
	
	protected $_modelImovel;
	protected $_modelFoto;
	protected $_modelEndereco;
	
	//--------------------------------------------------------------------------
	
	protected $_foto;
	protected $_endereco;
	protected $_imovel;
    
	//--------------------------------------------------------------------------
	
	public function __construct(Zend_Db_Table_Row $imovel)
	{
		$this->_modelFoto = new Application_Model_FotoImovel();
		$this->_modelEndereco = new Application_Model_Endereco();
		
		return $this->_mapper($imovel);
	}
	
	//--------------------------------------------------------------------------
	
	protected function _mapper(Zend_Db_Table_Row $imovel)
	{
		$this->_imovel = $imovel;
		$id = $imovel->id_imovel;
		$idEndereco = $imovel->endereco_id;
		
		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$foto = $this->_modelFoto->buscar($id);
		
		foreach ($foto as $row) 
		{
			$arrayFoto[$row->id_foto] = $row; 
		}
		$this->_foto = array_shift($arrayFoto);
		
		$arrayEndereco = array();
		foreach ($endereco as $row) 
		{ 
			$arrayEndereco = $row; 
		}
		$this->_endereco = $arrayEndereco;
		
		return $this;
	}
	
	//--------------------------------------------------------------------------
	
	public function getImovel()
	{
		return $this->_imovel;
	}
	
	//--------------------------------------------------------------------------
	
	public function getFoto()
	{
		return $this->_foto;
	}
	
	//--------------------------------------------------------------------------
		
	public function getEndereco()
	{
		return $this->_endereco;
	}
	
	//--------------------------------------------------------------------------
	
	public function setImovel($imovel)
	{
		$this->_imovel = $imovel;
	}
	
	//--------------------------------------------------------------------------
	
	public function setFoto($foto)
	{
		$this->_foto = $foto;
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
		switch ($this->getImovel()->situacao)
		{
			case Application_Model_Imovel::SITUACAO_ATIVO:
				return 'Ativo';
				break;
			 
		 	case Application_Model_Imovel::SITUACAO_INATIVO:
				return 'Inativo';
				break;
			
			default:
				return 'Status Desconhecido';
				break;
		}
	}
	
	//--------------------------------------------------------------------------
	  
	/**
	* Retorna a zona endereco do usuário
	 *
	* @param  int     $zona
	* @return string
	*/
	public function getZona()
	{
		switch ($this->getEndereco()->zona)
		{
			case Application_Model_Endereco::ZONA_LESTE:
				return 'Leste';
				break;
		 
			case Application_Model_Endereco::ZONA_NORTE:
				return 'Norte';
				break;
		 
			case Application_Model_Endereco::ZONA_OESTE:
				return 'Oeste';
				break;
		 
		 	case Application_Model_Endereco::ZONA_SUL:
				return 'Sul';
				break;
			
			default:
				return 'Status Desconhecido';
				break;
		}
	}
	
	//--------------------------------------------------------------------------
    
	public function getTipoImovel()
	{
		switch ($this->getImovel()->tipo_imovel)
		{
			case Application_Model_Imovel::TIPO_CASA:
				return 'Casa';
				break;
	
			case Application_Model_Imovel::TIPO_TERRENO:
				return 'Terreno';
				break;
					
			case Application_Model_Imovel::TIPO_PREDIO:
				return 'Prédio';
				break;
	
			default:
				return 'Status Desconhecido';
			break;
		}
	}
	
	//--------------------------------------------------------------------------
	    
	public function getStatusImovel()
	{
		switch ($this->getImovel()->status_imovel)
		{
			case Application_Model_Imovel::STATUS_ALUGADO:
				return 'Alugado';
				break;
					
			case Application_Model_Imovel::STATUS_ALUGANDO:
				return 'Alugando';
				break;
	
			case Application_Model_Imovel::STATUS_SUSPENSO:
				return 'Suspenso';
				break;
	
			case Application_Model_Imovel::STATUS_VENDIDO:
				return 'Vendido';
				break;
	
			case Application_Model_Imovel::STATUS_VENDENDO:
				return 'Vendendo';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}
	
	//--------------------------------------------------------------------------
	
	public function getCategoriaImovel()
	{
		switch ($this->getImovel()->categoria_imovel)
		{
			case Application_Model_Imovel::CATEGORIA_COMERCIAL:
				return 'Comercial';
				break;
	
			case Application_Model_Imovel::CATEGORIA_INDUSTRIAL:
				return 'Industria';
				break;
	
			case Application_Model_Imovel::CATEGORIA_RESIDENCIAL:
				return 'Residêncial';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}
	
	//--------------------------------------------------------------------------
	
	public function getChaveImovel()
	{
		switch ($this->getImovel()->local_chave)
		{
			case Application_Model_Imovel::CHAVE_CLIENTE:
				return 'Cliente';
				break;
	
			case Application_Model_Imovel::CHAVE_CONSTRUTORA:
				return 'Construtora';
				break;
	
			case Application_Model_Imovel::CHAVE_IMOBILIARIA:
				return 'Imobiliaria';
				break;
	
			case Application_Model_Imovel::CHAVE_PORTARIA:
				return 'Portaria';
				break;
	
			case Application_Model_Imovel::CHAVE_VIZINHO:
				return 'Vizinho';
				break;
	
			case Application_Model_Imovel::CHAVE_ZELADOR:
				return 'Zelador';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}
	
	//--------------------------------------------------------------------------
}