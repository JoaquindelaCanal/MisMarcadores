<?= $this->Html->css('login') ?>
<?= $this->Html->script('login') ?>

<div class="container">

<div class="row" style="margin-top:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <?= $this->Flash->render('auth') ?>
        <?= $this->Form->create() ?>
	        <fieldset>
				<h2><center><b>Iniciar Sesión</b></center></h2>
				<hr class="colorgraph"><center>
				<div class="form-group">

                    <?= $this->Form->input('email', ['class' => 'form-control input-lg', 'placeholder' => 'Correo Electrónico', 'label' => false, 'required']) ?>
				</div>
				<div class="form-group">
                    
                    <?= $this->Form->input('password', ['class' => 'form-control input-lg', 'placeholder' => 'Contraseña', 'label' => false, 'required']) ?>
				</div>
				</center>
				<hr class="colorgraph">
				<center>
				<div class="row">
					<center><div class="divLogIn">
                        
                        <?= $this->Form->button('Ingresar' , ['class' => 'btn btn-lg btn-success btn-block'])   ?> 
					</div></center>
				</div>
			</fieldset>
		<?= $this->Form->end(); ?>
	</div>
</div>
