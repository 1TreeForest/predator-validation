<?php $visible_depend = []; if(!isset($active_tab)){ $active_tab = false; } ?>

<?php foreach($form->getFormStructure($data) as $fieldset_id => $fieldset){ ?>

<?php if ($fieldset['type'] == 'html'){ ?>
    <div id="fset_<?php echo $fieldset_id; ?>"><?php if (!empty($fieldset['content'])) { echo $fieldset['content']; } ?></div>
    <?php continue; ?>
<?php } ?>

<?php if (empty($fieldset['is_empty']) && empty($fieldset['childs'])) { continue; } ?>
    <?php if(empty($attributes['only_fields'])){ ?>
        <?php if($active_tab === false){ $active_tab = (string)$fieldset_id; } ?>
        <div id="tab-<?php echo $fieldset_id; ?>" class="tab-pane<?php if($active_tab === (string)$fieldset_id){ ?> active<?php } ?>">
    <?php } ?>
    <fieldset id="fset_<?php echo $fieldset_id; ?>" class="<?php if (!empty($fieldset['is_collapsed'])){ ?>is_collapsed <?php if (!empty($fieldset['collapse_open'])){ ?>do_expand<?php } else { ?>is_collapse<?php } ?><?php } ?><?php if (isset($fieldset['class'])){ ?><?php echo $fieldset['class']; ?><?php } ?>"
    <?php if (isset($fieldset['is_hidden'])){ ?>style="display:none"<?php } ?>>

        <?php if (!empty($fieldset['title']) && !$form->is_tabbed){ ?>
            <legend><?php echo $fieldset['title']; ?></legend>
        <?php } ?>

        <?php if (is_array($fieldset['childs'])){ ?>
        <?php foreach($fieldset['childs'] as $field) {

                if(!is_array($field)){ $_field = [$field]; } else { $_field = $field; }

                $first_field_key = array_keys($_field)[0]; $lang_active_tab = true;

                if(!is_numeric($first_field_key)){ ?>

                    <ul class="nav nav-tabs flex-wrap mb-2 field_tabbed<?php echo $_field[$first_field_key]->visible_depend ? ' child_field' : ''; ?>">
                        <?php foreach ($_field as $key => $field) { ?>
                        <li class="nav-item field_tabbed_<?php echo $key; ?>">
                            <a class="nav-link <?php echo $lang_active_tab ? 'active' : ''; $lang_active_tab = false; ?>" href="#f_<?php echo $field->id; ?>">
                                <?php echo isset($field->field_tab_title) ? $field->field_tab_title : ''; ?>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>

                <?php }

                foreach ($_field as $key => $field) {

                    $name = $field->getName();

                    if (is_array($errors) && isset($errors[$name])){
                        $error = $errors[$name];
                    } else {
                        $error = false;
                    }

                    $value = $field->getDefaultValue();

                    if (strpos($name, ':') !== false){
                        $name_parts = explode(':', $name);
                        $_value = array_value_recursive($name_parts, $data);
                        if ($_value !== null){
                            $value = $_value;
                        }
                    } else {
                        if (is_array($data) && array_key_exists($name, $data)){
                            $value = $data[$name];
                        }
                    }

                    if ($error && !is_array($error)){
                        $field->classes[] = 'field_error';
                    }

                    if($field->visible_depend){
                        $visible_depend[] = $field;
                    }

                    if ($field->is_hidden || $field->getOption('is_hidden')) {

                        echo html_input('hidden', $field->element_name, $value, ['id' => $field->id]);

                        continue;
                    }

                    $input_html = $field->{$field->display_input}($value);
                    if(!$input_html){
                        continue;
                    }

                ?>

                <div id="<?php echo 'f_'.$field->id; ?>" class="form-group <?php echo implode(' ', $field->classes); ?>" <?php if (isset($field->rel)) { ?>rel="<?php echo $field->rel; ?>"<?php } ?> <?php if ($field->styles) { ?>style="<?php echo implode(';', $field->styles); ?>"<?php } ?>>

                    <?php echo $input_html; ?>

                    <?php if(!empty($field->hint) || !empty($field->patterns_hint['patterns']) || $error) { ?>
                        <div class="d-flex justify-content-between icms-forms-hint">
                        <?php if(!empty($field->hint) || !empty($field->patterns_hint['patterns'])) { ?>
                            <div class="hint form-text text-muted small mt-1"<?php
                                if(!empty($field->patterns_hint['patterns'])){
                                    echo ' data-spacer="'.(isset($field->patterns_hint['spacer']) ? $field->patterns_hint['spacer'] : ' ').'"';
                                    echo ' data-spacer_stop="'.htmlspecialchars(json_encode(!empty($field->patterns_hint['spacer_stop']) ? $field->patterns_hint['spacer_stop'] : [','=>2,'.'=>2,':'=>2,';'=>2,'!'=>2,'?'=>2,'-'=>3,'|'=>3,'—'=>3])).'"';
                                }
                            ?>>
                                <?php if(!empty($field->hint)) { echo $field->hint; } ?>
                                <?php if(!empty($field->patterns_hint['patterns'])){ ?>
                                    <span class="pattern_fields_panel_hint">
                                        <?php echo isset($field->patterns_hint['text_panel']) ? $field->patterns_hint['text_panel'] : LANG_CP_SEOMETA_HINT_PANEL; ?>
                                    </span>
                                    <span class="icms-forms-pattern__fields<?php if(empty($field->patterns_hint['always_show'])){ ?> pattern_fields_panel<?php } ?>" data-for_id="<?php echo $field->id; ?>">
                                        <?php echo sprintf((!empty($field->patterns_hint['text_pattern']) ? $field->patterns_hint['text_pattern'] : LANG_CP_SEOMETA_HINT_PATTERN.LANG_CP_SEOMETA_HINT_PATTERN_DOC), implode(' ', $field->patterns_hint['pattern_fields'])); ?>
                                        <?php echo !empty($field->patterns_hint['text_help']) ? $field->patterns_hint['text_help'] : ''; ?>
                                    </span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($error && !is_array($error)){ ?><div class="invalid-feedback w-auto ml-auto"><?php echo $error; ?></div><?php } ?>
                        </div>
                    <?php } ?>
                </div>

            <?php }

        } } ?>

        </fieldset>
    <?php if(empty($attributes['only_fields'])){ ?>
    </div>
    <?php } ?>

<?php } ?>
<?php ob_start(); ?>
<script>
    initMultyTabs('.field_tabbed');
    <?php if($visible_depend){ foreach($visible_depend as $field){ ?>
        icms.forms.addVisibleDepend('<?php echo $attributes['form_id']; ?>', '<?php echo $field->name; ?>', <?php echo json_encode($field->visible_depend); ?>);
    <?php } ?>
        icms.forms.VDReInit();
    <?php } ?>
</script>
<?php $this->addBottom(ob_get_clean()); ?>
