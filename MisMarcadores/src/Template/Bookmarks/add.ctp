<div class="row">
    <div class="col-md-6 col-md-offset-3">
            <div>
                <br><legend>Crear Enlace</legend> <br>
            </div>
        <?= $this->Form->create($bookmark, ['novalidate']) ?>
        <fieldset>
            <?= $this->element('bookmarks/fields')?>
        </fieldset>
        <?= $this->Form->button('Añadir Enlace', ['class' => 'btn-primary']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>