<?= $this->Html->css('bookmarks') ?>
        <div class="page-header">
            <h2>
                Mi Lista
                <?= $this->Html->link('<span class="glyphicon glyphicon-plus"></span> Nuevo', ['controller' => 'Bookmarks', 'action' => 'add'], ['class' => 'btn btn-primary pull-right', 'escape' => false]); ?>
            </h2>
        </div>
<div class="container">
    <div class="col-sm-12"> <!--default, primary, info, success, warning, danger  -->
        <?php foreach ($bookmarks as $bookmark): ?>
            <div class="bs-calltoaction bs-calltoaction-info">
                <div class="row">
                    <div class="col-md-9 cta-contents">
                        <h2 class="t-bk"><?= h($bookmark->title) ?></h2>
                        <b><?= $this->Html->link($bookmark->url, null, ['target' => '_blank', 'escape' => false]) ?></b> 
                        <div class="cta-desc">
                            <p></br><?= h($bookmark->description) ?> </p>
                        </div>
                    </div>
                    <div class="col-md-3 cta-button">
                        <?= $this->Html->link('Editar', ['controller' => 'Bookmarks', 'action' => 'edit', $bookmark->id], ['class' => 'btn btn-md btn-info']) ?>
                        <?= $this->Form->postLink('Eliminar', ['controller' => 'Bookmarks', 'action' => 'delete', $bookmark->id], ['confirm' => 'Eliminar enlace ?', 'class' => 'btn btn-md btn-danger']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>    
    </div>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->prev('< Anterior') ?>
                <?= $this->Paginator->numbers(['before' => '', 'after' => '']) ?>
                <?= $this->Paginator->next('Siguiente >') ?>
            </ul>
            <p><?= $this->Paginator->counter() ?></p>
        </div>
</div>