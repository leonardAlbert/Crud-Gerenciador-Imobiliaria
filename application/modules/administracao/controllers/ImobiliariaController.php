<?php
/**
 * Administracao_ImobiliariaController
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Administracao_ImobiliariaController extends Controller
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
		$this->_model = new Application_Model_Imobiliaria();
		$this->_form = new Administracao_Form_Imobiliaria();
		
		$this->_cacheList = 'imobiliaria';
		$this->_listagem = '/administracao/imobiliaria/listar';

		parent::init();
	}

	//--------------------------------------------------------------------------
	
}