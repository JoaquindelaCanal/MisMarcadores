<div class="row">
    <div class="col-md-6 col-md-offset-3">
            <div>
                <br><legend>Editar Enlace</legend> <br>
            </div>
        <?= $this->Form->create($bookmark, ['novalidate']) ?>
        <fieldset>
            <?= $this->element('bookmarks/fields')?>
        </fieldset>
        <?= $this->Form->button('Confirmar', ['class' => 'btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>