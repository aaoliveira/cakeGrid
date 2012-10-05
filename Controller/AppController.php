<?php
/**
 * Controlador Geral
 *
 * PHP 5
 *
 * @copyright	Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		http://cakephp.org CakePHP(tm) Project
 * @package		app.Controller
 * @since		CakePHP(tm) v 0.2.9
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');
/**
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	/**
	 * Determina se um método pode ou não retornar.
	 * 
	 * @var		string
	 */
	public $naoVoltar	= false;

	/**
	 * Executa código antes de qualquer outra função do controlador.
	 *
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeFilter()
	{
		// usuário off só pode acessar tela de login
		if (!$this->Session->check('Usuario.id'))
		{
			if ($this->name!='Usuarios' && $this->action!='login')
			{
				$this->redirect($this->base.'/usuarios/login');
			}
		}
	}

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		$this->viewVars['onRead'] = isset($this->viewVars['onRead']) ? $this->viewVars['onRead'] : '';

		// se ocorreu erro de banco de dados, redireciona para instalação do banco ou das tabelas do módulo sistema
		if (isset($this->viewVars['code']) && $this->viewVars['code']==500 && $this->name=='CakeError')
		{
			if (strpos($this->viewVars['name'],'1049') || strpos($this->viewVars['name'],'1045'))
				$this->redirect(array('controller' => 'instala', 'action' => 'instala_bd'));
			elseif (isset($this->viewVars['class']) && $this->viewVars['class']=='Usuario')
				$this->redirect(array('controller' => 'instala', 'action' => 'instala_tb'));
		}

		// definindo o módulo ativos
		$this->setModulo();
	}

	/**
	 * Joga na view o modulo e seus cadastros (controllers correntes)
	 * 
	 * @return	void
	 */
	public function setModulo()
	{
		$plugin	= empty($this->plugin) ? 'Sistema' : $this->plugin;

		$this->viewVars['pluginAtivo'] 	= $plugin;
		$this->viewVars['cadastros']	= array();

		$arq = $plugin=='Sistema' ? APP : APP.'Plugin'.DS.$plugin.DS;
		$arq .= 'Config'.DS.'cadastros.php';
		if (file_exists($arq))
		{
			require_once($arq);
			$this->viewVars['cadastros'] = $cadastros;
		}
	}

	/**
	 * Exibe a tela principal do cadastro ativo
	 * 
	 * @return	void
	 */
	public function index()
	{
		$this->redirect('lista');
	}

	/**
	 * Exibe o dbgrid do banco de dados
	 * 
	 * @return	void
	 */
	public function lista()
	{
		$msg							= '';
		$modelClass 					= $this->modelClass;
		$pag							= array();
		$this->viewPath 				= 'Crud';
		$this->viewVars['acoesLista'] 	= isset($this->viewVars['acoesLista']) 
		? $this->viewVars['acoesLista'] 
		: array('delete'=>'Excluir','exportar'=>'Exportar');

		// se o form foi postado
		if (isset($this->data['btSalvar']))
		{
			if (isset($this->data[$this->name]))
			{
				unset($this->request->data['pgSl']);
				unset($this->request->data['btSalvar']);
				unset($this->request->data['acaoLista']);
				if (!$this->$modelClass->saveAll($this->data[$this->name]))
				{
					$this->viewVars['erros'] = $this->$modelClass->validationErrors;
				} else
				{
					$this->Session->setFlash('Os Registros foram salvos com sucesso !!!','default',array('class'=>'msgOk'));
				}
			}
		}

		// se a lista foi postada
		if (isset($this->data['acaoLista']) && isset($this->data['cx']))
		{
			$acaoLista 			= $this->data['acaoLista'];
			$cx					= $this->data['cx'];
			$this->naoVoltar	= true;
			foreach($cx as $_id => $_vlr)
			{
				if (method_exists($this,$acaoLista))
				{
					$this->$acaoLista($_id);
				} else $msg = 'Não foi possível executar '.$acaoLista;
			}
			if (empty($msg)) $msg = 'A ação selecionada foi executada com sucesso !!!';
			$this->Session->setFlash($msg,'default',array('class'=>'msgOk'));
		}

		// popula visão
		$this->setVisao();

		// ordem padrão
		$pag['sort'] = isset($this->$modelClass->displayField) ? $this->$modelClass->displayField : 'id';

		// variáveis formatadas para a view
		$this->viewVars['msg'] = $msg;

		// recuperando a páginação do cadastro corrente pela url
		$pag['page'] 		= isset($this->passedArgs['pag']) 		? $this->passedArgs['pag'] 		: 1;
		$pag['sort'] 		= isset($this->passedArgs['ordem']) 	? $this->passedArgs['ordem'] 	: $pag['sort'];
		$pag['direction'] 	= isset($this->passedArgs['dire']) 		? $this->passedArgs['dire'] 	: 'asc';

		// paginando
		$this->params['named'] 	= $pag;
		$this->data	= $this->paginate();
	}

	/**
	 * Exibe a tela de exclusão do registro corrente.
	 * 
	 * @param	integer		$id 	Id do registro a ser excluĩdo
	 * @return	void
	 */
	public function excluir($id=0)
	{
		$modelClass 					= $this->modelClass;
		$this->viewPath 				= 'Crud';
		$this->viewVars['modelClass'] 	= $modelClass;
		$this->data						= $this->$modelClass->find('all',array('conditions'=>array('id'=>$id)));
	}

	/**
	 * Executa a exclusão do registro corrente no banco de dados.
	 * 
	 * @param	integer	$id	Id do registro a ser excluído
	 * @return	void
	 */
	public function delete($id=0)
	{
		$modelClass = $this->modelClass;
		if ($this->$modelClass->deleteAll($modelClass.'.id='.$id.'', true, true))
		{
			$this->Session->setFlash('O registro foi excluído com sucesso !!!','default',array('class'=>'msgOk'));
		} else
		{
			$this->Session->setFlash('Erro ao tentar excluir registro','default',array('class'=>'msgErro'));
			$this->Session->write('edicao_erros',$this->$modelClass->validationErrors);
		}
		if (!$this->naoVoltar) $this->redirect('lista');
	}

	/**
	 * Executa a exportação, em CSV, dos registros que foram selecionada na lista.\n
	 * 
	 * @param	array	Matriz contendo todo os Ids que serão exportados.
	 * @return	void
	 */
	public function exportar()
	{
		$this->Session->setFlash('Os registros foram exportados com sucesso !!!','default',array('class'=>'msgOk'));
		if (!$this->naoVoltar) $this->redirect('lista');
	}

	/**
	 * Retorno pesquisa para selectBox
	 * 
	 * @return	void
	 */
	public function combo($campo='', $cmp2='nome', $valor=0)
	{
		$this->layout		= 'ajax';
		$this->viewPath 	= 'Crud';
		$modelClass 		= $this->modelClass;

		// filtro
		$condicao[$campo]	= $valor;

		// campos que serão exibidos
		$registros			= array('id');
		array_push($registros,$cmp2);

		$lista_combo		= $this->$modelClass->find('list',array('conditions'=>$condicao,'fields'=>$registros,'order'=>$cmp2));
		$this->set(compact('lista_combo'));
	}

	/**
	 * Popula a visão com as lista de cada campo relacionado.
	 * 
	 * @return	void
	 */
	public function setVisao()
	{
		$modelClass 		= $this->modelClass;

		if (isset($this->$modelClass->table) && !empty($this->$modelClass->table))
		{
			$this->viewVars['schema'][$modelClass] = isset($this->viewVars['schema'][$modelClass]) ? $this->viewVars['schema'][$modelClass] : $this->$modelClass->schema();

			// descobrindo os valores de associação
			if (!empty($this->viewVars['schema'][$modelClass]))
			{
				foreach($this->viewVars['schema'][$modelClass] as $_cmp => $_arrProp)
				{
					// se a ordem da lista não foi definida, mas existe um campo nome, então usa ela.
					if (!isset($this->listaCampo) && $_cmp=='nome')
					{
						$this->$modelClass->displayField = 'nome';
					}
				}
			}
		}

		$this->setAssociacoes();

		$this->viewVars['simnaos']['1'] = 'Sim';
		$this->viewVars['simnaos']['0'] = 'Não';
		$this->viewVars['sexos']['M'] 	= 'Masculino';
		$this->viewVars['sexos']['F'] 	= 'Feminino';
		$this->viewVars['modelClass'] 	= $modelClass;
	}

	/**
	 * Joga na view o conteúdo de campos associados.\n
	 * 
	 * @return	void
	 */
	private function setAssociacoes()
	{
		$modelClass = $this->modelClass;
		$associacoes = $this->$modelClass->getAssociated();
		if (count($associacoes))
		{
			foreach($associacoes as $_model => $_tipo)
			{
				$parametros = array();
				$condicao 	= array();
				if (isset($this->$modelClass->belongsTo[$_model]['conditions'])) $condicao = $this->$modelClass->belongsTo[$_model]['conditions'];
				$parametros['conditions'] 	= $condicao;
				$parametros['limit'] 		= 10000;
				$chave	= empty($this->$modelClass->$_model->useTable) ? $this->$modelClass->$_model->useTable : strtolower(Inflector::pluralize($_model));
				if (!isset($this->viewVars[$chave]))
				{
					$this->set($chave,$this->$modelClass->$_model->find('list',$parametros));
				}
			}
		}
	}
}
