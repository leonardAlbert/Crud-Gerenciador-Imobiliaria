<?php

/**
 * Application_Model_Imovel
 *
 * Entidade responsável pela persistencia dos dados
 *
 * @package TCC
 * @author  Leonard Albert <leonard.mastrochirico@gmail.com>
 * @since   1.0
 */
class Application_Model_Imovel extends Zend_Db_Table
{
	/**
	 * Nome da tabela utilizada, Imovel
	 * @var unknown_type
	 */
	protected $_name = 'imovel';

	//--------------------------------------------------------------------------

	/**
	 * Chave primaria da tabela Imovel
	 * @var unknown_type
	 */
	protected $_primary = 'id_imovel';

	//--------------------------------------------------------------------------

	protected $_endereco;

	//--------------------------------------------------------------------------

	protected $_foto;

	//--------------------------------------------------------------------------

	protected $_fotoImovel;

	//--------------------------------------------------------------------------

	/**
	 * Constantes utilizadas para identificar a situação deste usuário
	 *
	 * @var int
	 */
	const SITUACAO_ATIVO = 1;
	const SITUACAO_INATIVO = 2;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const TIPO_CASA		= 1;
	const TIPO_TERRENO	= 2;
	const TIPO_PREDIO	= 3;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const STATUS_ALUGADO 	= 1;
	const STATUS_ALUGANDO	= 2;
	const STATUS_SUSPENSO	= 3;
	const STATUS_VENDIDO	= 4;
	const STATUS_VENDENDO 	= 5;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const CATEGORIA_COMERCIAL 	= 1;
	const CATEGORIA_INDUSTRIAL 	= 2;
	const CATEGORIA_RESIDENCIAL = 3;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	const CHAVE_CLIENTE 	= 1;
	const CHAVE_CONSTRUTORA = 2;
	const CHAVE_IMOBILIARIA = 3;
	const CHAVE_PORTARIA 	= 4;
	const CHAVE_VIZINHO 	= 5;
	const CHAVE_ZELADOR 	= 6;

	//--------------------------------------------------------------------------

	/**
	 *
	 * Enter description here ...
	 * @var unknown_type
	 */
	protected $_cols = array(
		'id_imovel',
		'imovel',
		'promotor',
		'anotacoes',
		'tipo_imovel',
		'status_imovel',
		'categoria_imovel',
		'local_chave',
		'valor_imovel',
		'ano_construcao',
		'dormitorios',
		'suite',
		'vagas_carro',
		'data_cadastro',
		'ultima_visita',
		'situacao',
		'endereco_id',
		'metragem_terreno',
		'valor_iptu',
		'valor_condominio',
		'area_lazer',
		'area_construida',
		'total_comodos',
		'area_servico'
		);

		//--------------------------------------------------------------------------

		public function __construct()
		{
			$this->_endereco = new Application_Model_Endereco();
			$this->_foto = new Application_Model_Fotos();

			$this->_fotoImovel = new Application_Model_FotoImovel();

			parent::__construct();
		}

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
			$zDate = new Zend_Date($dados['data_cadastro'], Zend_Registry::get('locale'));
			$dados['data_cadastro'] = $zDate->getIso();

			$zDate = new Zend_Date($dados['ultima_visita'], Zend_Registry::get('locale'));
			$dados['ultima_visita'] = $zDate->getIso();

			$imovel = $this->_filtrar($dados);

			try
			{
				$endereco = $this->_endereco->cadastrar($dados);
				$foto = $this->_foto->cadastrar($dados);

				$imovel['endereco_id'] = $endereco;
				$imovel = $this->insert($imovel);

				$data = array(
					'id_imovel'	=> $imovel,
					'id_foto'	=> $foto
				);

				$fotoImovel = $this->_fotoImovel->insert($data);
			}
			catch(Exception $e)
			{
				$endereco = FALSE;
				$foto = FALSE;
				$imovel = FALSE;
				$fotoImovel = FALSE;

				return "O imovel não pode ser cadastrado: {$e->getMessage()}";
			}

			return $imovel;
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

			if (isset($data['id_imovel']))
			{
				unset($data['id_imovel']);
			}

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

		//--------------------------------------------------------------------------


		public function listar(array $where = NULL, $order = NULL, $situacao = NULL, array $like = NULL)
		{
			$select = $this->select()->from($this->_name);

			if($where != NULL)
			{
				foreach($where as $coluna => $valor)
				{
					$select->where($coluna, $valor);
				}
			}

			if($like != NULL)
			{
				foreach($like as $coluna => $valor)
				{
					$select->where("$coluna LIKE ?", $valor.'%');
				}

			}

			if($order != NULL) $select->order($order);

			if($situacao != NULL) $select->where('situacao = ?', $situacao);

			$list = $this->fetchAll($select);

			if($list->current() == NULL) return NULL;

			$imoveis = array();
			foreach($list as $row)
			{
				$imoveis[] = new ImovelMapper($row);
			}

			return $imoveis;
		}

		//--------------------------------------------------------------------------

		public function buscar($id)
		{
			$where = array('id_imovel = ?' => (int) $id);

			$imovel = $this->listar($where);

			if($imovel == null) return null;

			return array_shift($imovel);
		}

		//--------------------------------------------------------------------------


		public function pesquisaDetalhada($dados)
		{
			// Remover as linhas vazias para busca
			foreach ($dados as $row => $value)
			{
				if(empty($dados[$row]))
				{
					unset($dados[$row]);
				}
			}

			// Armazena os "id's para realizar a busca dos imoveis (id_endereco).
			$id = array();

			// Pesquisa pelo valor do imovel.
			if (isset($dados['valor_imovel']) && $dados['valor_imovel'] != 'R$ 0,00')
			{
				$where = array('valor_imovel' => $dados['valor_imovel']);
					
				foreach ($where as $row => $value)
				{
					$select->where("$row <= ?", $value);
				}
					
				$imovel = $this->buscaValor($where);
					
				if ( count($imovel) != 0 )
				{
					foreach ($imovel as $row)
					{
						$id[] = $row['endereco_id'];
					}
				}

				unset($dados['valor_imovel']);
			}

			// Pesquisa pelo valor do iptu.
			if (isset($dados['valor_iptu']) && $dados['valor_iptu'] != 'R$ 0,00')
			{
				$where = array('valor_imovel' => $dados['valor_iptu']);
					
				foreach ($where as $row => $value)
				{
					$select->where("$row <= ?", $value);
				}
					
				$imovel = $this->buscaValor($where);

				if ( count($imovel) != 0 )
				{
					foreach ($imovel as $row)
					{
						$id[] = $row['endereco_id'];
					}
				}

				unset($dados['valor_iptu']);
			}


			// Pesquisa pelo valor do condominio.
			if (isset($dados['valor_condominio']) && $dados['valor_condominio'] != 'R$ 0,00')
			{
				$where = array('valor_imovel' => $dados['valor_condominio']);
					
				foreach ($where as $row => $value)
				{
					$select->where("$row <= ?", $value);
				}
					
				$imovel = $this->buscaValor($where);

				if ( count($imovel) != 0 )
				{
					foreach ($imovel as $row)
					{
						$id[] = $row['endereco_id'];
					}
				}

				unset($dados['valor_condominio']);
			}

			// Pesquisa pelo nome do imovel.
			if (isset($dados['imovel']))
			{
				$where = array('imovel' => $dados['imovel']);
					
				foreach ($where as $row => $value)
				{
					$select->where("$row <= ?", $value);
				}
					
				$imovel = $this->buscaLike($where);

				if ( count($imovel) != 0 )
				{
					foreach ($imovel as $row)
					{
						$id[] = $row['endereco_id'];
					}
				}

				unset($dados['imovel']);
			}

			$imovel = $this->buscaLike($dados);
			$endereco = $this->_endereco->buscaLike($dados);

			// Extrair o "endereco_id" dos endereços localizados
			if($imovel != NULL)
			{
				$imovel = $imovel->toArray();
				foreach ($imovel as $row)
				{
					$id[] = $row['endereco_id'];
				}
			}

			// Extrair o "id_imovel" dos imoveis localizados
			if ($endereco != NULL)
			{
				$endereco = $endereco->toArray();
				foreach ($endereco as $row)
				{
					$id[] = $row['id_endereco'];
				}
			}

			// Remove os "Id's" duplicados do array.
			$id = array_unique($id);

			// Verifica se o id não é nulo.
			if ($id == NULL) return NULL;

			// Realiza a busca dos Id's no banco de dados.
			$lista = array();
			foreach ($id as $row => $value)
			{
				$where = array('endereco_id = ?' => $value);
				if ($this->listar($where) != NULL)
				{
					$lista[] = $this->listar($where);
				}
			}

			if ($lista == NULL) return NULL;

			return $lista;
		}

		//--------------------------------------------------------------------------

		public function buscaLike(array $like)
		{
			$like = $this->_filtrar($like);
			if ($like == NULL) return NULL;

			$select = $this->select()->from($this->_name);

			foreach($like as $coluna => $valor)
			{
				$select->where("$coluna LIKE ?", '%'.$valor.'%');
			}

			return $this->fetchAll($select);
		}

		//--------------------------------------------------------------------------

		public function buscaValor($dados)
		{
			$where = $this->_filtrar($dados);

			$select = $this->select()->from($this->_name);

			foreach ($where as $row => $value)
			{
				$select->where("$row <= ?", $value);
			}

			return $this->fetchAll($select);
		}

		//--------------------------------------------------------------------------

}

