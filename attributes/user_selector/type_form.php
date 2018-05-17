<fieldset>
    <legend><?= t('Select Options') ?></legend>
    <div class="clearfix"></div>
    <div class="input">
        <div class="form-group">
            <label><?=$form->checkbox('allowMultiple', 1, $allowMultiple) ?> <?= t('Allow selection of multiple Users') ?></label>
        </div>
    </div>
</fieldset>
