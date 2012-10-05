<?php
/**
 * Model Usuário
 */
class Usuario extends AppModel {
	/**
	 * Tabela no banco de dados
	 * 
	 * @var		string
	 * @access	public
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable		= 'usuarios';

	/**
	 * Campo principal do model
	 * 
	 * @var		string
	 * @access	public
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#displayfield
	 */
	public $displayField	= 'login';

	/**
	 * Regras de validação para cada campo do módulo
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#validate
	 * @link	http://book.cakephp.org/2.0/en/models/data-validation.html
	 */
	public $validate = array
	(
		'login' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o login do Usuário!',
				'on'		=> 'create'
			),
			2 	=> array
			(
				'rule' 		=> 'isUnique',
				'required' 	=> 'create',
				'message' 	=> 'Este login já foi cadastro!',
			)
		),
		'senha'	=> array
            (
				1	=> array
				(
					'rule'		=> 'notEmpty',
					'required'	=> true,
					'message'	=> 'A senha é obrigatória !',
					'on'		=> 'create'
				)
            ),
	);

	/**
	 * Relacionamento 1 para n
	 * 
	 * @var 	array
	 * @link 	http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#belongsto
	 */
	public $belongsTo = array
	(
		'Cidade' => array
		(
			'className' 	=> 'Cidade',
			'foreignKey' 	=> 'cidade_id',
			'fields' 		=> array('Cidade.id','Cidade.nome'),
			'order'			=> 'Cidade.nome',
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
		'Grupo' => array
		(
			'className'				=> 'Grupo',
			'joinTable'				=> 'grupos_usuarios',
			'associationForeignKey' => 'grupo_id',
			'foreignKey'			=> 'usuario_id',
			'unique'				=> true,
			'fields' 				=> array('Grupo.id','Grupo.nome'),
			'order'					=> 'Grupo.nome',
		)
	);

	/**
	 * Executa código antes da exclusão do usuário no banco de dados.
	 * O Usuário administrador, com id igual a 1 NÃO pode ser excluído.
	 *
	 * @param	boolean		$cascade	Se Verdadeiro exclui também as dependências
	 * @return	boolean		Retorna verdadeiro se excluíu com sucesso, falso em caso contrário
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
	 */
	public function beforeDelete($cascade = true)
	{
		if ($this->id==1) return false;
		return true;
	}
}
