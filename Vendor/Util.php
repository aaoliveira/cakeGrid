<?php
/**
 * 
 */
class Util {
	/**
	 * Executa a instalação do banco de dados
	 * 
	 * @return boolean
	 */
	public function getInstalaSql($dir='',$arq='')
	{
		// limpando o cache model na marra
		if(!in_array(strtolower(PHP_OS),array('winnt','windows','win','w')))
		{
			$dirCache	= APP . 'tmp' . DS . 'cache' . DS . 'models'. DS;
			$ponteiro	= opendir($dirCache);
			//while ($nome_itens = readdir($ponteiro)) if (!in_array($nome_itens,array('.','..','vazio'))) unlink($dirCache.$nome_itens);
		}

		// arquivo
		$arq = $dir.$arq.'.sql';

		// instancio o datasource só pra pegar erros do banco
		App::uses('ConnectionManager', 'Model');
		$bd = ConnectionManager::getDataSource('default');

		// instala todas as tabelas do saae
		if (!file_exists($arq))
		{
			$this->erro = 'Não foi possível localicar o arquivo '.$arq;
			exit('não foi possível localizar o arquivo '.$arq);
			return false;
		}
		$handle  = fopen($arq,"r");
		$texto   = fread($handle, filesize($arq));
		$sqls	 = explode(";",$texto);
		fclose($handle);
		foreach($sqls as $sql) // executando sql a sql
		{
			if (trim($sql))
			{
				try
				{
					$this->Ferramenta->query($sql, $cachequeries=false);
				} catch (exception $ex)
				{
					die('erro ao executar: '.$sql.'<br />'.$ex->getMessage());
				}
			}
		}

		// descobrindo os arquivos CSV
		$arrCsv 	= array();
		$ponteiro	= opendir($dir);
		while ($nome_itens = readdir($ponteiro))
		{
			$arrNome = explode('.',$nome_itens);
			if (strtolower($arrNome['1'])=='csv') array_unshift($arrCsv,$arrNome['0']);
		}

		// atualiza outras tabelas vias CSV
		foreach($arrCsv as $tabela)
		{
			$this->setPopulaTabela($dir.$tabela.'.csv',$tabela,$bd);
		}

		return true;
	}

	/**
	 * Popula uma tabela do banco com seu aquivo CSV
	 * 
	 * @parameter 	$arq	string	Caminho completo com o nome do arquivo
	 * @parameter	$tabela	string	Nome da tabela a ser populada
	 * @parameter	$db		object	Instancia de banco de dados
	 * @return		boolean
	 */
	private function setPopulaTabela($arq='',$tabela='',$db=null)
	{
		// mandando bala se o csv existe
		if (file_exists($arq))
		{
			$handle  	= fopen($arq,"r");
			$l 			= 0;
			$campos 	= '';
			$cmps	 	= array();
			$valores 	= '';

			// executando linha a linha
			while ($linha = fgetcsv($handle, 2048, ";"))
			{
				if (!$l)
				{
					$i = 0;
					$t = count($linha);
					foreach($linha as $campo)
					{
						$campos .= $campo;
						$i++;
						if ($i!=$t) $campos .= ',';
					}
					// montand os campos da tabela
					$arr_campos = explode(',',$campos);
				} else
				{
					$valores  = '';
					$i = 0;
					$t = count($linha);
					foreach($linha as $valor)
					{
						if ($arr_campos[$i]=='created' || $arr_campos[$i]=='modified') $valor = date("Y-m-d H:i:s");
						$valores .= "'".str_replace("'","\'",$valor)."'";
						$i++;
						if ($i!=$t) $valores .= ',';
					}
					$sql = 'INSERT INTO '.$tabela.' ('.$campos.') VALUES ('.$valores.')';
					try
					{
						$this->Ferramenta->query($sql, $cachequeries=false);
					} catch (exception $ex)
					{
						die('erro ao executar: '.$sql.'<br />'.$ex->getMessage());
					}
				}
				$l++;
			}
			fclose($handle);

			// verificando se a tabela possui created e modified
			try
			{
				$res = $this->Ferramenta->query('SHOW FULL COLUMNS FROM '.$tabela, $cachequeries=false);
			} catch (exception $ex)
			{
				die('erro ao executar: recuperar lista de tabelas<br />'.$ex->getMessage());
			}
			foreach($res as $_linha => $_arrColunas)
			{
				if ($_arrColunas['COLUMNS']['Field']=='modified')	array_push($cmps,'modified');
				if ($_arrColunas['COLUMNS']['Field']=='created')	array_push($cmps,'created');
			}
			if (count($cmps))
			{
				$sql = '';
				foreach($cmps as $_campo) $sql .= "$_campo='".date("Y-m-d H:i:s")."', ";
				$sql = substr($sql,0,strlen($sql)-2);
				$sql = 'UPDATE '.$tabela.' SET '.$sql;
				try
				{
					$this->Ferramenta->query($sql, $cachequeries=false);
				} catch (exception $ex)
				{
					die('erro ao executar: '.$sql.'<br />'.$ex->getMessage());
				}
			}

		} else echo 'não foi possivel localizar '.$arq.'<br />';

		return true;
	}
}

