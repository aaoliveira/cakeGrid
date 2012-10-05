<?php $this->viewVars['titulo'] = 'Informações do Usuário Logado'; ?>
<?php $ds = $this->Session->read('Usuario'); ?>
<?php $ds['cidade'] = $this->viewVars['cidades'][$ds['cidade_id']] ?>
<?php $ds['ativo'] = $this->viewVars['simnaos'][$ds['ativo']] ?>
<?php unset($ds['id']); unset($ds['cidade_id']); ?>

<br /><br /><br />
<center><a href='<?= $this->base ?>/usuarios'><img src='../img/fechar2.png' /></a></center>

<div id='info'>

	<br />
	<h4>Informações do Usuário</h4>
	<br />
	<ul>
	<?php foreach($ds as $_cmp => $_vlr) : ?>
	<?php if ($_cmp=='Grupos') : ?>
	<li><label>Grupos:</label>
		<ul style='margin: 0px 0px 0px 145px;'>
			<?php foreach($_vlr as $_l => $_arrCmps) : ?>
			<li><?= $_arrCmps['nome'] ?></li>
			<?php endforeach ?>
		</ul>
	</li>
	<?php elseif ($_cmp=='Cadastros') : ?>
	<li><label>Cadastros:</label>
		<ul style='margin: 0px 0px 0px 145px;'>
			<?php foreach($_vlr as $_l => $_arrMods) : ?>
			<li><?= $_arrMods['Modulo']['tituloModulo'].'.'.$_arrMods['Cadastro']['tituloCadastro'] ?></li>
			<?php endforeach ?>
		</ul>
	</li>
	<?php else : ?>
	<li><label><?= $_cmp ?>: </label><?= $_vlr ?></li>
	<?php endif ?>
	<?php endforeach ?>
	</ul>
	<br />

</div>
