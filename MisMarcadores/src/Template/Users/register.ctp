<?= $this->Html->css('register') ?>
<?= $this->Html->script('register') ?>

<div class="container">
	<div class="row">
    	<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<h2><b>Crea tu cuenta </b><small>Es gratis y lo seguira siendo.</small></h2>
			<hr class="colorgraph">
			<?= $this->Form->create($user) ?>
			<fieldset>
            	<?= $this->element('users/fields')?>
			    <div class="row">
					<center><div class="col-xs-8 col-sm-9 col-md-9">
					 Haciendo click en <strong class="label label-primary">Registrarse</strong>, aceptas los <a href="#" data-toggle="modal" data-target="	#t_and_c_m">Terminos y Condiciones </a> de nuestro sitio, incluido 	el uso de cookies.
						</div></center>
			    </div>
			<hr class="colorgraph">
			<div class="row">
			    <div class="col-xs-12 col-md-6">
				<?= $this->Form->button('Registrarse' , ['class' => 'btn btn-primary btn-block btn-lg']) ?></div>
				<div class="col-xs-12 col-md-6">
				<?php echo $this->Html->link(
				        'Iniciar Sesión',
				        '/users/login',
				        array('class' => 'btn btn-info btn-block btn-lg')
				    ); ?>
			    </div>
			</div>
			</fieldset>
		    <?= $this->Form->end() ?>	
			
		</div>
	</div>
       <?= $this->element('users/terms')?>
</div>