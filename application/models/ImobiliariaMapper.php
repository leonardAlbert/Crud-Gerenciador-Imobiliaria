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
class ImobiliariaMapper
{
	//--------------------------------------------------------------------------

	protected $_modelImobiliaria;
	protected $_modelTelefone;
	protected $_modelEndereco;

	//--------------------------------------------------------------------------

	protected $_telefone;
	protected $_endereco;
	protected $_imobiliaria;

	//--------------------------------------------------------------------------

	public function __construct(Zend_Db_Table_Row $imobiliaria)
	{
		$this->_modelTelefone = new Application_Model_TelefoneImobiliarias();
		$this->_modelEndereco = new Application_Model_Endereco();

		return $this->_mapper($imobiliaria);
	}

	//--------------------------------------------------------------------------

	protected function _mapper(Zend_Db_Table_Row $imobiliaria)
	{
		$this->_imobiliaria = $imobiliaria;

		$id = $imobiliaria->id_imobiliaria;
		$idEndereco = $imobiliaria->endereco_id;
		

		$endereco = $this->_modelEndereco->buscar($idEndereco);
		$telefone = $this->_modelTelefone->buscar($id);

		foreach ($telefone as $row)
		{
			$arrayTelefone[$row->id_telefone] = $row;
		}
		$this->_telefone = array_shift($arrayTelefone);

		$arrayEndereco = array();
		foreach ($endereco as $row)
		{
			$arrayEndereco = $row;
		}
		$this->_endereco = $arrayEndereco;

		return $this;
	}

	//--------------------------------------------------------------------------

	public function getImobiliaria()
	{
		return $this->_imobiliaria;
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

	public function setImobiliaria($Imobiliaria)
	{
		$this->_imobiliaria = $Imobiliaria;
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
		switch ($this->getImobiliaria()->situacao)
		{
			case Application_Model_Imobiliaria::SITUACAO_ATIVO:
				return 'Ativo';
				break;

			case Application_Model_Imobiliaria::SITUACAO_INATIVO:
				return 'Inativo';
				break;
					
			default:
				return 'Status Desconhecido';
			break;
		}
	}

	//--------------------------------------------------------------------------

}