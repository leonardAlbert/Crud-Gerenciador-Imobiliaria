<?php

class Administracao_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->_redirect('administracao/consultorio/exibir');
    }


}