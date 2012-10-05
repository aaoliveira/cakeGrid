<?php ?>
<ul>
	<li>Sistema
		<ul>
			<li><a href='<?= $this->base ?>/usuarios/'>Usuários</a></li>
			<li><a href='<?= $this->base ?>/grupos/'>Grupos</a></li>
			<li><hr></li>
			<li><a href='<?= $this->base ?>/usuarios/limpar_cache'>Limpar Cache</a></li>
		</ul>
	</li>
	<li>Ajuda
	<ul>
		<li><a href=''>Usuários on-line</a></li>
		<li><a href=''>Sobre o <?= Configure::read('sistema'); ?></a></li>
	</ul>
	</li>
</ul>
