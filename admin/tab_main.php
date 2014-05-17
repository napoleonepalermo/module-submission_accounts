<?php

if (isset($request["update"]))
{
	$request["tab"] = "main";
  list ($g_success, $g_message) = sa_update_submission_account($form_id, $request);
}

// get a list of forms that already have a submission account configured. These are omitted from the
// list of available forms
$submission_accounts = sa_get_submission_accounts();
$omit_forms = array();
foreach ($submission_accounts as $configured_form)
{
	if ($configured_form["form_id"] != $form_id)
    $omit_forms[] = $configured_form["form_id"];
}

$js = sa_get_form_view_mapping_js();
$submission_account = sa_get_submission_account($form_id);

$form_fields = ft_get_form_fields($form_id);

// ------------------------------------------------------------------------------------------------

$page_vars = array();
$page_vars["submission_account"] = $submission_account;
$page_vars["omit_forms"] = $omit_forms;
$page_vars["form_id"] = $form_id;
$page_vars["tabs"] = $tabs;
$page_vars["page"] = $page;
$page_vars["form_fields"] = $form_fields;
$page_vars["js_messages"] = array("phrase_please_select", "phrase_please_select_form");
$page_vars["head_string"] = "<script type=\"text/javascript\" src=\"../global/scripts/manage_submission_account.js\"></script>";
$page_vars["head_js"] = "
$js

var rules = [];
rules.push(\"required,view_id,{$L["validation_no_view_id"]}\");
rules.push(\"required,theme,{$LANG["validation_no_theme"]}\");
rules.push(\"required,username_field_id,{$L["validation_no_username_field"]}\");
rules.push(\"required,password_field_id,{$L["validation_no_password_field"]}\");
";

ft_display_module_page("templates/admin/edit.tpl", $page_vars);