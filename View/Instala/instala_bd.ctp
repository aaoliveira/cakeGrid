<?php ?>
<div id='instala_bd' style='margin: 50px auto; width: 500px;'>
<p>Não foi possível conectar ao <strong>banco de dados</strong>, peça ao Administrador do banco de dados para executar:</p>

<pre>
create database <?php echo $bd['database']; ?> CHARACTER SET <?= $bd['encoding'] ?>;
grant all privileges on <?php echo $bd['database']; ?>.* to <?php echo $bd['login']."@".$bd['host']." identified by \"".$bd['password']."\" with grant option;\n"; ?>
flush privileges;
</pre>

<p>Clique <a href='<?= $this->base?>/usuarios/login'>aqui</a> para tentar novamente.</p>

</div>
