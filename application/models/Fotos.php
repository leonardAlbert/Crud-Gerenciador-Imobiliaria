<?php
/**
 * Application_Model_Clientes
 *
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Fotos extends Zend_Db_Table
{
	//--------------------------------------------------------------------------

	/**
	 * Nome da tabela utilizada, Clientes
	 * @var unknown_type
	 */
	protected $_name = 'fotos';

	//--------------------------------------------------------------------------

	/**
	 * Chave primaria da tabela clientes
	 * @var unknown_type
	 *
	 */
	protected $_primary = 'id_foto';

	//--------------------------------------------------------------------------


	protected $_cols = array(
		'id_foto',
		'foto',
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

	public function cadastrar($dados)
	{
		$fotos = $this->_filtrar($dados);
		try
		{
			$idFoto = $this->insert($fotos);
		}
		catch(Exception $e)
		{
			return "A foto não pode ser cadastrado {$e->getMessage()}";
		}

		return $idFoto;
	}

	//--------------------------------------------------------------------------

	// @ TODO
	public function atualizar($id, $dados)
	{
		$foto = $this->_filtrar($dados);

		try
		{
			$foto = $this->update($id, $dados);
		}
		catch(Exception $e)
		{
			return "O foto não pode ser Atualizado {$e->getMessage()}";
		}
			
		return "nada rs";
	}

	//--------------------------------------------------------------------------

	protected function _filtrar($dados)
	{
		$data = array();
			
		foreach($this->_cols as $value)
		{
			if( array_key_exists($value, $dados) )
			{
				$data[$value] = $dados[$value];
			}
		}
		unset($data[$this->_primary]);
			
		return $data;
	}

	//--------------------------------------------------------------------------

	private function _dadosCadastro($dados)
	{
		foreach($this->_cols as $key => $value)
		{
			if( ! isset($dados[$key]) )
			unset($dados[$key]);
		}

		return $dados;
	}

}