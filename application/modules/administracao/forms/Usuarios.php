<?php
/**
 * Administracao_Form_Usuarios
 *
 * Formulário de cadastro e alteração
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Administracao_Form_Usuarios extends Zend_Form
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
		$this->setName('usuarios');
		 
		$this->cadastrar();
	}

	//--------------------------------------------------------------------------

	public function cadastrar()
	{
		$this->addElements(
		array(
		$this->_getId(),
		$this->_getNome(),
		$this->_getLogin(),
		$this->_getSenha(),
		$this->_getConfirmarSenha(),
		$this->_getSituacao(),
		$this->_getPerfil(),
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

	private function _getNome()
	{
		$nome = new Zend_Form_Element_Text('nome');
		$nome->setLabel('Nome')
		->setAttrib('placeholder', 'Nome')
		->setRequired(true)
		->setAllowEmpty(false)
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_StringLength( array(5, 145) ))
		->addValidator( new Zend_Validate_NotEmpty() )
		->setAttrib('size', '25');

		return $nome;
	}

	//--------------------------------------------------------------------------

	private function _getSenha()
	{
		$senha = new Zend_Form_Element_Password('senha');
		$senha->setLabel('Senha')
		->setAttrib('placeholder', 'Senha')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '10')
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_NotEmpty() )
		->addValidator( new Zend_Validate_StringLength(5, 25) );
			
		return $senha;
	}

	//--------------------------------------------------------------------------

	private function _getConfirmarSenha()
	{
		$senha = new Zend_Form_Element_Password('confirmar_senha');
		$senha->setLabel('Confirmar Senha')
		->setAttrib('placeholder', 'Confirmar Senha')
		->setRequired(true)
		->setAllowEmpty(false)
		->setAttrib('size', '10')
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_NotEmpty() )
		->addValidator( new Zend_Validate_StringLength(5, 25) );
			
		return $senha;
	}

	//--------------------------------------------------------------------------

	private function _getLogin()
	{
		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('Login')
		->setAttrib('placeholder', 'Login')
		->setRequired(true)
		->setAllowEmpty(false)
		->addFilter( new Zend_Filter_StripTags() )
		->addFilter( new Zend_Filter_StringTrim() )
		->addValidator( new Zend_Validate_StringLength( array(8, 100) ))
		->addValidator( new Zend_Validate_Db_NoRecordExists(
		array('table'   => 'usuarios',
			  'field'   => 'login'
              )))
        ->setAttrib('size', '25');

        return $login;
	}

	//--------------------------------------------------------------------------

	private function _getSituacao()
	{
		$situacao = new Zend_Form_Element_Hidden('situacao');
		$situacao->setValue(Application_Model_Usuarios::SITUACAO_ATIVO);

		return $situacao;
	}

	//--------------------------------------------------------------------------

	private function _getPerfil()
	{
		$perfil = new Zend_Form_Element_Radio('perfil');
		$perfil->setLabel('Perfil')
		->setRequired(true)->setAllowEmpty(false)
		->addMultiOption(Application_Model_Usuarios::PERFIL_ADMINISTRADOR, 'Administrador')
		->addMultiOption(Application_Model_Usuarios::PERFIL_USUARIO, 'Usuário');

		return $perfil;
	}

	//--------------------------------------------------------------------------

	private function _getSubmit()
	{
		$salvar = new Zend_Form_Element_Submit('autenticar');
		$salvar->setLabel('Salvar');

		return $salvar;
	}

	//--------------------------------------------------------------------------

	/**
	 * Formulário de alteração dados do usuário sem alterar sua senha
	 *
	 * @param  Zend_Db_Table_Row $row
	 * @return Zend_Form
	 */
	public function formAlterar(Zend_Db_Table_Row $row)
	{
		$this->setAction("/administracao/usuarios/alterar/id/{$row->id_usuario}");

		$this->getElement('login')
		->getValidator('Db_NoRecordExists')
		->setExclude(array(
                 'field' => 'id_usuario',
                 'value' => $row->id_usuario
		)
		);

		$this->removeElement('senha');
		$this->removeElement('confirmar_senha');

		$situacao = new Zend_Form_Element_Radio('situacao');
		$situacao->setLabel('Situação')
		->setAllowEmpty(false)
		->setRequired(true)
		->addMultiOption(Application_Model_Usuarios::SITUACAO_ATIVO, 'Ativo')
		->addMultiOption(Application_Model_Usuarios::SITUACAO_INATIVO, 'Inativo');

		$this->addElement($situacao);
			
		$this->populate($row->toArray());

		return $this;
	}

	//--------------------------------------------------------------------------

	/**
	 * Formulário de alteração da senha do usuário
	 *
	 * @param  Zend_Db_Table_Row $row
	 * @return Zend_Form
	 */
	public function formAlterarSenha(Zend_Db_Table_Row $row)
	{
		$this->setAction("/administracao/usuarios/alterar-senha/id/{$row->id_usuario}");

		$this->removeElement('nome');
		$this->removeElement('login');
		$this->removeElement('perfil');

		$this->removeElement('situacao');

		$this->populate($row->toArray());

		return $this;
	}

	//--------------------------------------------------------------------------

}