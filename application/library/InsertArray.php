<?php
/**
 * Insert Array (matriz de inserção)
 *
 * Biblioteca utilizada para efetuar um processamento de um array vindo de um 
 * POST para que possa ser inserido no banco de dados sem campos de submit entre
 * outros.
 *
 * INSTALAÇÃO: Basta jogar dentro da pasta library do seu projeto em Zend Framework. 
 *
 * @package	library
 * @name	InsertArray
 */
class InsertArray
{
	/**
	 * Obtém os dados para inserção no banco de dados em forma de array.
	 *
	 * @name	getDataForInsert
	 * @access	static
	 * @param	array	$post - array vindo de uma requisição POST
	 * @param	object	$model - Objeto que herda de Zend_Db_Table
	 * @return 	array
	 */
    public static function getDataForInsert(array $post, $model)
    {
    	if(empty($post) || ! is_object($model)) return NULL;
    	
    	$cols = $model->getCols();
    	$data = array();
    	
    	foreach($cols as $value)
    	{
			if( array_key_exists($value, $post) )
			{
				$data[$value] = $post[$value];
			}
		}
    	return $data;
    }
    
}   