<?php  ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
<?php echo $this->fetch('meta'); ?>

<title><?php if (isset($this->viewVars['titulo'])) echo $this->viewVars['titulo']; ?></title>

<?php echo $this->Html->meta('icon')."\n"; ?>
<?php echo $this->Html->css('reset')."\n"; ?>
<?php echo $this->Html->css('default')."\n"; ?>
<?php echo $this->Html->css(strtolower($this->name))."\n"; ?>
<?php echo $this->fetch('css'); ?>


<script type="text/javascript" src="<?= $this->base; ?>/js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="<?= $this->base; ?>/js/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="<?= $this->base; ?>/js/default.js"></script>
<script type="text/javascript" src="<?= $this->base; ?>/js/<?= strtolower($this->name) ?>.js"></script>
<?php echo $this->fetch('script'); ?>

<script type="text/javascript">
var url = '<?= $this->base; ?>';
$(document).ready(function()
{
	setTimeout(function(){ $("#flashMessage").fadeOut(4000); },3000);
	<?php echo $this->viewVars['onRead']; ?>

});
</script>

</head>
<body>
<div id='corpo'>

	<div id='cabecalho'>
		<?php echo $this->Session->flash(); ?>

		<div id='imgSistema'>
			<img src='<?= $this->base ?>/img/<?= strtolower(Configure::read('sistema')) ?>.png' />
		</div>

		<div id='titSistema'>
			<span><a href='<?= $this->base ?>'><?= Configure::read('sistema') ?></a></span>
			<span>:: <a href='<?= $this->here ?>'><?= $pluginAtivo ?></a></span>
		</div>

		<div id='menuLogin'>
			<?php if ($this->Session->check('Usuario.id')) : ?>
			<ol>
				<li><a href='<?= $this->base ?>/usuarios/info'><?= $this->Session->read('Usuario.login'); ?></a></li> |
				<li><a href='<?= $this->base ?>/usuarios/sair'>sair</a></li>
			</ol>
			<?php else : ?>
			<ol>
				<li><a href='<?= $this->base ?>/usuarios/login'>login</a></li>
			</ol>
			<?php endif ?>
		</div>

	</div>

	<div id='pagina'>
		<?php echo $this->fetch('content'); ?>

	</div>

	<div id='rodape'>
		por Adriano C. de Moura<br />

	</div>

</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
