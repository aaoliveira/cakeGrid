<?php
/**
 * Cadastro de Usuários
 */
class UsuariosController extends AppController {
	/**
	 * Model
	 * 
	 * @var		array
	 * @access	public
	 */
	public $uses = array('Usuario');

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		$this->viewVars['schema']['Usuario'] = $this->Usuario->schema();
		$this->viewVars['schema']['Usuario']['login']['edicaoOff'] = true;
		parent::beforeRender();
	}

	/**
	 * Exibe a tela de informação do usuário.
	 * 
	 * @return	void
	 */
	public function login()
	{
		if ($this->Session->check('Usuario.id')) $this->redirect('info');

		$msg = 'Entre com o login e senha válidos ...';
		if (isset($this->data['Usuario']['login']) && !empty($this->data['Usuario']['login']))
		{
			App::uses('Security', 'Utility');
			$query = array();
			$query['conditions']['Usuario.login'] = $this->data['Usuario']['login'];
			$query['conditions']['Usuario.senha'] = Security::hash(Configure::read('Security.salt') . $this->data['Usuario']['senha']);
			$dataUs	= $this->Usuario->find('all',$query);
			if (empty($dataUs))
			{
				$msg = '<span class="spanErro">Usuário inválido !!!</span>';
				$this->Session->setFlash('Usuário Inválido !!!','default', array('class'=>'msgErro'));
			} else
			{
				$msg = '<span class="spanOk">Usuário autenticao com sucesso !!!</span>';
				unset($dataUs['0']['Usuario']['senha']);
				$dataUs['0']['Usuario']['Grupos'] = $dataUs['0']['Grupo'];
				$this->Session->write('Usuario',$dataUs['0']['Usuario']);
				$novaData['Usuario']['id'] 		= $dataUs['0']['Usuario']['id'];
				$novaData['Usuario']['login'] 	= $dataUs['0']['Usuario']['login'];
				$novaData['Usuario']['acessos'] = $dataUs['0']['Usuario']['acessos']+1;
				$novaData['Usuario']['ultimo'] 	= date('d-m-Y h:m:s');
				if (!$this->Usuario->save($novaData))
				{
					debug($this->Usuario->validationErrors);
					die('fudeu !');
				} else
				{
					$this->Session->setFlash('Usuário autenticado com sucesso !!!','default', array('class'=>'msgOk'));
					$this->redirect('index');
				}
			}
		}
		$this->set('msg',$msg);
	}

	/**
	 * Exibe a tela de informações do usuário logado.
	 * 
	 * @return	void
	 */
	public function info()
	{
		$this->setVisao();
	}

	/**
	 * Executa o log-off do usuário.
	 * 
	 * @return	void
	 */
	public function sair()
	{
		$this->Session->destroy();
		$this->Session->destroy();
		$this->redirect('/');
	}

	/**
	 * Exibe a tela de lista
	 * 
	 * @return	void
	 */
	public function lista()
	{
		$this->viewVars['listaCampos'] = array('Usuario.login','Usuario.nome','Usuario.email','Usuario.cidade_id');
		//$this->viewVars['comboCampos']['estado_id']['cidades'] = 'nome';
		parent::lista();
	}
}
