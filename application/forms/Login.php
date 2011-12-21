<?php
/**
 * Application_Form_Login
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Form_Login extends Zend_Form
{
	/**
	 * Inicia os dados do formulário
	 *
	 * @name	init
	 * @access	public
	 * @return 	void
	 */
	public function init()
	{
		$this->setName('login');
		 
		$this->login();
	}

	//--------------------------------------------------------------------------

	public function login()
	{
		$this->addElements(
			array(
				$this->_getId(),
				$this->_getLogin(),
				$this->_getSenha(),
				$this->_getSubmit()
			)
		);
	}

	//--------------------------------------------------------------------------

	private function _getId()
	{
		$id = new Zend_Form_Element_Hidden('id_usuario');
		 
		return $id;
	}

	//--------------------------------------------------------------------------

	private function _getLogin()
	{
		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('Login')
				->setRequired(true)
              	->setAllowEmpty(false)
              	->addFilter( new Zend_Filter_StripTags() )
              	->addFilter( new Zend_Filter_StringTrim() ) 
              	->addValidator( new Zend_Validate_StringLength( array(8, 100) ))
				->setAttrib('size', '20');
				
		return $login;
	}

	//--------------------------------------------------------------------------

	private function _getSenha()
	{
		$senha = new Zend_Form_Element_Password('senha');
		$senha->setLabel('Senha')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '20')
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_NotEmpty() )
		->addValidator( new Zend_Validate_StringLength(5, 255) );
			
		return $senha;
	}

	//--------------------------------------------------------------------------

	private function _getSubmit()
	{
		$salvar = new Zend_Form_Element_Submit('autenticar');
		$salvar->setLabel('Enviar');

		return $salvar;
	}

	//--------------------------------------------------------------------------

}