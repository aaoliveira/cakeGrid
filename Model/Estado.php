<?php
/**
 * Model Estado
 * 
 */
class Estado extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	public
	 */
	public $name 			= 'Estado';

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
	public $order		 	= 'Estado.nome';

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
				'message' 	=> 'É necessário informar o nome do Estado!',
			)
		)
	);

	/**
	 * Relacionamento 1 pra N.\n
	 * - Um Estado possui várias Cidades
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#hasmany
	 */
	public $hasMany = array
	(
        'Cidade' => array
        (
            'className'     => 'Cidade',
            'foreignKey'    => 'estado_id',
            'order'         => 'Cidade.nome',
            'limit'         => '1000',
            'dependent'     => true
        )
    );

	/**
	 * Executa código antes da exclusão de um estado no banco de dados.
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
