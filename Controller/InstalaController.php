<?php
/**
 * Classe de instalação
 */
class InstalaController extends AppController {
	/**
	 * Executa código antes da chamada de qualquer ação da classe.
	 * 
	 * @return	void
	 * @link 	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeFilter()
	{
		$this->layout = 'instala';
	}

	/**
	 * Executa código antes da renderização da view.
	 * 
	 * @return	void
	 * @link 	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeRender()
	{		
	}

	/**
	 * Exibe a tela de instalação do banco dd dados
	 * 
	 * @return	void
	 */
	public function instala_bd()
	{
		require_once(APP.DS.'Config'.DS.'database.php');
		$bd = new DATABASE_CONFIG();
		$this->set('bd',$bd->default);
	}

	/**
	 * Exibe a tela de instalação do banco dd dados
	 * 
	 * @return	void
	 */
	public function instala_tb()
	{
		App::uses('Util', 'Model');
		$Util = new Util();
		$Util->getInstalaSql(APP.'Docs'.DS.'Sql'.DS,strtolower(Configure::read('sistema')).'_bd');
	}
}
