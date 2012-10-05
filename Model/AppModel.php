<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	/**
	 * Consultas a fonte de dados e retorna uma matriz conjunto de resultados.
	 * 
	 * @param	string	$type	Type of find operation (all / first / count / neighbors / list / threaded)
	 * @param	array	$query	Option fields (conditions / fields / joins / limit / offset / order / page / group / callbacks)
	 * @param	boolean	$cache	Se configurado para Verdadeiro, busca a consulta no cache
	 * @return	array			Array of records
	 * @link	http://book.cakephp.org/2.0/en/models/deleting-data.html#deleteall
	 */
	public function find($type = 'first', $query = array(), $cache=false)
	{
		return parent::find($type,$query);
	}

	/**
	 * Executa código depois que foi execudada uma operação find.
	 * O resultado pode ser alterado.
	 *
	 * @param	mixed	$results	O Resultado da operação find.
	 * @param	boolean $primary	Se o modelo está sendo consultado diretametne
	 * @return	mixed				O Mesmo resultado, customizado se foi preciso.
	 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
	 */
	public function afterFind($results, $primary = false) 
	{
		if (isset($results['0']))
		{
			foreach($results as $_linha => $_arrModels)
			{
				foreach($_arrModels as $_model => $_arrCmps)
				{
					if (is_array($_arrCmps))
					{
						foreach($_arrCmps as $_cmp => $_vlr)
						{
							$tipo = $this->getColumnType($_cmp);
							switch($tipo)
							{
								case 'datetime':
									$vlr = substr($_vlr,8,2).'/'.substr($_vlr,5,2).'/'.substr($_vlr,0,4).' '.substr($_vlr,11,8);
									$results[$_linha][$_model][$_cmp] = $vlr;
									break;
								case 'date':
									$vlr = substr($_vlr,8,2).'/'.substr($_vlr,5,2).'/'.substr($_vlr,0,4);
									$results[$_linha][$_model][$_cmp] = $vlr;
									break;
							}
							if ($_model=='Usuario' && $_cmp=='senha') $results[$_linha][$_model][$_cmp] = '';
						}
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Chamado antes de cada operação de salvamento, após a validação. 
	 * Retornar um resultado não-verdadeira para deter a salvar.
	 *
	 * @param	array	$options
	 * @return	boolean	True if the operation should continue, false if it should abort
	 * @link 	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforesave
	 */
	public function beforeSave($options = array()) 
	{
		$this->ignorarCampos = isset($this->ignorarCampos) ? $this->ignorarCampos: array('login','senha','email');
		if ($this->data)
		{
			$schema = $this->schema();
			foreach($this->data as $_model => $_arrCmp)
			{
				foreach($_arrCmp as $_cmp => $_vlr)
				{
					$tipo = isset($schema[$_cmp]['type']) ? $schema[$_cmp]['type'] : 'string';
					switch($tipo)
					{
						case 'datetime': //12-02-2012 12:14:38
							$vlr = substr($_vlr,6,4).'-'.substr($_vlr,3,2).'-'.substr($_vlr,0,2).' '.substr($_vlr,11,8);
							$this->data[$_model][$_cmp] = $vlr;
							break;
						case 'date':
							$vlr = substr($_vlr,6,4).'-'.substr($_vlr,3,2).'-'.substr($_vlr,0,2);
							$this->data[$_model][$_cmp] = $vlr;
							break;
						default:
							if (!in_array($_cmp,$this->ignorarCampos) && is_string($_vlr))
							{
								$this->data[$_model][$_cmp] = mb_strtoupper($_vlr,'utf-8');
							}
							break;
					}

					// campo modificado
					if (isset($schema['modificado']))
					{
						$this->data[$_model]['modificado'] = date('Y-m-d H:i:s');
					}

					// campo criado
					if (isset($schema['criado']) && !isset($_arrCmp['id']))
					{
						$this->data[$_model]['criado'] = date('Y-m-d H:i:s');
					}
				}
			}
		}
		return true;
	}
}
