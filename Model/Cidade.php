<?php
/**
 * Model Cidade
 * 
 */
class Cidade extends AppModel {
	/**
	 * Tabela no banco de dados
	 * 
	 * @var		string
	 * @access	public
	 * @link	http://book.cakephp.org/2.0/en/models/model-attributes.html#usetable
	 */
	public $useTable		= 'cidades';

	/**
	 * Campo padrão do model
	 * 
	 * @var		string
	 * @access	public
	 */
	public $displayField 	= 'nome';

	/**
	 * Campo de ordenação padrão
	 * 
	 * @var		string
	 * @access	public
	 */
	public $order		 	= 'Cidade.nome';

	/**
	 * Regras de validação para cada campo do model
	 * 
	 * @var		array
	 * @access	public
	 */
	public $validate = array
	(
		'nome' => array
		(
			1 	=> array
			(
				'rule' 		=> 'notEmpty',
				'required' 	=> true,
				'message' 	=> 'É necessário informar o nome da Cidade!',
			)
		)
	);

	/**
	 * Relacionamento 1 para n
	 * 
	 * @var		array
	 * @access	public
	 */
	public $belongsTo = array(
		'Estado' => array
		(
			'className' 	=> 'Estado',
			'foreignKey'	=> 'estado_id',
			'conditions'	=> '',
			'fields' 		=> array('Estado.id','Estado.uf'),
		)
	);

	/**
	 * Executa código antes da exclusão de uma cidade no banco de dados.
	 * - Nenhum registro pode ser excluĩdo.
	 *
	 * @param	boolean		$cascade	Se Verdadeiro exclui também as dependências
	 * @return	boolean					Retorna verdadeiro se excluíu com sucesso, falso em caso contrário
	 * @link	http://book.cakephp.org/2.0/en/models/callback-methods.html#beforedelete
	 */
	public function beforeDelete($cascade = true)
	{
		return false;
	}
}
