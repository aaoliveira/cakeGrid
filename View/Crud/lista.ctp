<?php $this->viewVars['titulo'] = 'Listando '.$this->name ?>
<?php $this->Html->css('lista.css', null, array('inline' => false)); ?>
<?php $pg 			= $this->request->params['paging'][$modelClass]; ?>
<?php $cadAtivo		= empty($this->name) ? 'usuarios' : strtolower($this->name); ?>
<?php $listaCampos 	= isset($listaCampos) ? $listaCampos : array(); ?>
<?php if (empty($listaCampos)) foreach($schema[$modelClass] as $_cmp => $_arrTps) if ($_cmp!='id') array_push($listaCampos,$modelClass.'.'.$_cmp); ?>
<?php $this->viewVars['onRead'] .= '$("#acaoLista").change(function() { $("#'.$this->name.'ListaForm").submit(); });'."\n"; ?>
<?php if (!strpos($this->here,'pag:')) 		$this->here .= '/pag:1'; ?>
<?php if (!strpos($this->here,'ordem:')) 	$this->here .= '/ordem:'.$this->request->params['paging'][$modelClass]['options']['sort']; ?>
<?php if (!strpos($this->here,'dire:')) 	$this->here .= '/dire:asc'; ?>
<?php $naoEditar = array($modelClass.'.criado',$modelClass.'.modificado'); ?>
<?php $this->viewVars['onRead'] .= "\t".'var pgC='.$pg['page'].";\n"; ?>
<?php $this->viewVars['onRead'] .= "\t".'var aqui="'.$this->here.'"'.";\n"; ?>
<?php $this->viewVars['onRead'] .= "\t".'$("#pgSl").change(function() { var p = $(this).val(); var url= aqui.replace("pag:"+pgC,"pag:"+p); window.location.href= url; });'."\n"; ?>


<div id='lista'>

<?php echo $this->Form->create($this->name) ?>
<div id='listaBarra'>
	<div id='listaNavegacao'>
		<ol>
			<?php if ($pg['prevPage']) : ?>
			<li><a href='<?= str_replace('pag:'.$pg['page'],'pag:1',$this->here); ?>'> << </a></li>
			<li><a href='<?= str_replace('pag:'.$pg['page'],'pag:'.($pg['page']-1),$this->here); ?>'> < </a></li>
			<?php else : ?>
			<li> << </li>
			<li> < </li>
			<?php endif ?>
			
			<li id='pgLi'>
				<select name='pgSl' id='pgSl'>
					<?php for($i=1; $i<$pg['pageCount']+1; $i++) : ?>
					<?php if ($i==$pg['page']) : ?>
					<option value='<?= $i ?>' selected="selected"><?= $i ?></option>
					<?php else : ?>
					<option value='<?= $i ?>'><?= $i ?></option>
					<?php endif ?>
					<?php endfor ?>
				</select>
			</li>
			
			<?php if ($pg['nextPage']) : ?>
			<li><a href='<?= str_replace('pag:'.$pg['page'],'pag:'.($pg['page']+1),$this->here); ?>'> > </a></li>
			<li><a href='<?= str_replace('pag:'.$pg['page'],'pag:'.$pg['pageCount'],$this->here); ?>'> >> </a></li>
			<?php else : ?>
			<li> > </li>
			<li> >> </li>
			<?php endif ?>

		</ol>
	</div>
	<div id='listaBotoes'>
		<input type='button' name='btAtual'  id='btAtual'  value='Atualizar' onclick='javascript:window.location.href="<?= $this->here ?>"'/>
		<input type='submit' name='btSalvar' id='btSalvar' value='Salvar Todos&nbsp;&nbsp;' />
		<?php echo $this->Form->input('acaoLista', array('name'=>'data[acaoLista]','id'=>'acaoLista','type'=>'select','options'=>$acoesLista,'empty'=>'-- Aplicar aos Marcadores --','label'=>false,'div'=>false)); ?>
	</div>
</div>

<div id='listaMenu'>
<ol>
	<?php foreach($cadastros as $_con => $_tit) : ?>
	<li>
		<?php if (strtolower($this->name)==strtolower($_con)) : ?>
			<span><?= $_tit ?></span>
		<?php else : ?>
			<a href='<?= $this->base.'/'.strtolower($_con).'/lista'; ?>'><?= $_tit ?></a>
		<?php endif ?>
	</li>
	<?php endforeach ?>
</ol>
</div>

<div id='listaTab'>
<table class='lista' id='tab<?= $modelClass ?>'>
<?php $l=0; foreach($this->data as $_l => $_arrModels) : ?>
<?php $id =  $_arrModels[$modelClass]['id']; ?>

	<?php if (!$l) : ?>
	<tr id='tr<?= $l ?>'>
	<?php foreach($listaCampos as $_cmp) : $a = explode('.',$_cmp); ?>
		<?php $prop  = isset($schema[$a['0']][$a['1']])? $schema[$a['0']][$a['1']] : array(); 				// propriedades do campo ?>
		<?php $tit	 = isset($input['label']) 			? $input['label'] : ucfirst(strtolower($a['1'])); 	// título ?>
		<?php $tit 	 = strpos($tit,'_id') 				? str_replace('_id','',$tit) : $tit; ?>

		<th id='th<?= $this->Form->domId($a['1']) ?>' class='th<?= ucfirst(strtolower($a['1'])) ?>'>
			<?php if (isset($prop['key'])) : ?>
				<?php $dire = strpos($this->here,'dire:asc') ? array('asc','desc') : array('desc','asc'); ?>
				<?php $link = str_replace('dire:'.$dire['0'],'dire:'.$dire['1'],$this->here) ?>
				<?php $link = str_replace('ordem:'.$this->request->params['paging'][$modelClass]['options']['sort'],'ordem:'.$a['1'],$link) ?>
				<a href='<?= $link ?>'><?= $tit ?></a>
			<?php else : ?>
				<?= $tit ?>
			<?php endif ?>
		</th>
	<?php endforeach; ?>
	<th>#</th>
	</tr>
	<?php endif; $l++; ?>

	<tr id='tr<?= $l ?>'>
	<?php foreach($listaCampos as $_cmp) : $a = explode('.',$_cmp); ?>
		<?php $prop  = isset($schema[$a['0']][$a['1']]) ? $schema[$a['0']][$a['1']] : array(); 			// propriedades do campo ?>
		<?php $input = isset($prop['input']) 			? $prop['input'] : array(); 						// propriedades do input ?>
		<?php if (isset($prop['edicaoOff'])) array_push($naoEditar,$_cmp); 								// verificando se o campo foi proibido de editar ?>

		<?php echo $this->Form->input($_l.'.'.$modelClass.'.id',array('type'=>'hidden','value'=>$id,'label'=>false, 'div'=>false)); ?>
		<?php $vlr = $_arrModels[$modelClass][$a['1']]; ?>

		<td class='td<?= $this->Form->domId($_cmp) ?>'>
		<?php
		if (in_array($_cmp,$naoEditar))
		{
			echo $vlr;
		} else
		{
			$opcoes = array();
			$opcoes['value'] 	= $vlr;
			$opcoes['label'] 	= false;
			$opcoes['div']		= false;
			$opcoes['class']	= 'in'.ucfirst(strtolower($a['1']));
			if ($prop['type']=='boolean')
			{
				$opcoes['options'] = array('1'=>'Sim','0'=> 'Não');
				if (!$vlr) $opcoes['value'] = '0';
			}
			echo $this->Form->input($_l.'.'.$_cmp,$opcoes);
		}
		?>
		</td>
	<?php endforeach; ?>
	<td><input type='checkbox' name='data[cx][<?= $id ?>]' id='cx<?= $id ?>' /></td><!-- ferramentas -->
	</tr>

<?php endforeach;  ?>

<tr>
	<td colspan='<?= count($listaCampos)+1; ?>'>
	Total <?= number_format($this->request['paging'][$modelClass]['count'],0,',','.') ?>
	</td>
</tr>
</table>
</div>

</div>
<?php echo $this->Form->end(); ?>
<?php if (isset($erros)) : ?>
<div id='listaErros'>
	<ol>
	<?php foreach($erros as $_linha => $_arrCmp) : ?>
	<?php foreach($_arrCmp as $_cmp => $_arrErr) : ?>
	<?php foreach($_arrErr as $_lEr => $_erro) : ?>
	<li><?= $_cmp.','.$_erro ?></li>
	<?php endforeach ?>
	<?php endforeach ?>
	<?php endforeach ?>
	</ol>
</div>
<?php endif ?>
