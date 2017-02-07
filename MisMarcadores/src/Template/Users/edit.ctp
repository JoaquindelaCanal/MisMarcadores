<div class="row">
    <div class="col-md-6 col-md-offset-3">
            <div>
                <br><legend>Editar Usuario</legend> <br>
            </div>
        <?= $this->Form->create($user) ?>
        <fieldset>
            <?= $this->element('users/fields') ?>
            <?php echo $this->Form->input('role', ['options' => ['user' => 'Usuario', 'admin' => 'Administrador'], 'label' => 'Rol']); ?>
        </fieldset>
        <?= $this->Form->button('Editar Usuario', ['class' => 'btn-info']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
