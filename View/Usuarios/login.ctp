<?php $this->viewVars['titulo'] = 'Login'; ?>
<?php $this->viewVars['onRead'] .= '$("#UsuarioLogin").focus();'."\n"; ?>

<div id='login' class='edicao'>
	<?= $this->Form->create('Login'); ?><br />
	<?= $this->Form->input('Usuario.login',array('label'=>'Login: ','type'=>'text')); ?><br />
	<?= $this->Form->input('Usuario.senha',array('label'=>'Senha: ','type'=>'password')); ?><br />
	<br />
	<center><?= $this->Form->end('Entrar'); ?></center>

</div>

<?php if (isset($msg)) : ?>
<div id='msg'>
	<center><?= $msg; ?></center>
</div>
<?php endif ?>
