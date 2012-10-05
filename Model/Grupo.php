<?php
/**
 * Model Modulo
 */
class Grupo extends AppModel {
	/**
	 * Tabela no banco de dados
	 * 
	 * @var		string
	 * @access	public
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable		= 'grupos';

	/**
	 * Campo principal do model
	 * 
	 * @var		string
	 * @access	public
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField	= 'nome';

	/**
	 * Regras de validação para cada campo do módulo
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#validate
	 * @link	http://book.cakephp.org/2.0/en/models/data-validation.html
	 */
	public $validate = array
	(
		'nome' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o nome do Grupo!',
			)
		)
	);

	/**
	 * Relacionamento n para n
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#hasandbelongstomany-habtm
	 */
	public $hasAndBelongsToMany	= array
	(
		'Usuario' => array
		(
			'className'				=> 'Usuario',
			'joinTable'				=> 'grupos_usuarios',
			'associationForeignKey' => 'usuario_id',
			'foreignKey'			=> 'grupo_id',
			'unique'				=> true,
			'fields' 				=> array('Usuario.id','Usuario.login')
		)
	);
	

	/**
	 * Executa código antes da exclusão do grupo no banco de dados.
	 * O Grupo Administrador, com id igual a 1, NÃO pode ser excluído.
	 *
	 * @param	boolean		$cascade	Se Verdadeiro exclui também as dependências
	 * @return	boolean					Retorna verdadeiro se excluíu com sucesso, falso em caso contrário
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
	 */
	public function beforeDelete($cascade = true)
	{
		if ($this->id==1) return false;
		return true;
	}
}
