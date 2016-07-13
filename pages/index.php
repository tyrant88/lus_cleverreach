<?php

/** @var rex_addon $this */

echo rex_view::title($this->i18n('lus_cleverreach_title'));

if (rex_post('config-submit', 'boolean')) {
	$this->setConfig(rex_post('config', [
		['apikey', 'string'],
		['groupid', 'string'],
		['formid', 'string'],
		['source', 'string'],
	]));

	echo rex_view::success($this->i18n('lus_cleverreach_config_saved'));
}
$apikey = $this->getConfig("apikey");
$groupid = $this->getConfig("groupid");
$formid = $this->getConfig("formid");
$source = $this->getConfig("source");

$htmlgroup = array();
$htmlform = array();
$selectgroupid = new rex_select();
$selectformid = new rex_select();

if ( empty($apikey) ) {
	$htmlgroup[] = $this->i18n('lus_cleverreach_select_apikey');
} else {
	try {
		$api = new CleverreachAPI($apikey);
		$result = $api->getGroupList();
		$selectgroupid->setSize(1);
		$selectgroupid->setName('config[groupid]');
		if ( !empty($groupid) ) { $selectgroupid->setSelected($groupid); }

		if ($result != false && $result->status == "SUCCESS") {
			$selectgroupid->addOption($this->i18n('lus_cleverreach_select_groupid'),-1);
			foreach ($result->data as $dataset) {
				$selectgroupid->addOption( $dataset->name, $dataset->id);
			}
			$htmlgroup[] = $selectgroupid->get();
		} else {
			$htmlgroup[] = $this->i18n('lus_cleverreach_groupid_failure');
		}

	} catch (Exception $e) {
		$htmlgroup[] = $this->i18n('lus_cleverreach_api_failure');
	}

}
if (empty($groupid)) {
	$htmlform[] = $this->i18n('lus_cleverreach_select_groupid');
} else {
	if (!empty($apikey)) {
		try {
			$api = new CleverreachAPI($apikey);
			$result = $api->getFormsList($groupid);
			$selectformid->setSize(1);
			$selectformid->setName('config[formid]');
			if ( !empty($formid) ) {$selectformid->setSelected($formid); }

			if ($result != false && $result->status == "SUCCESS") {
				$selectformid->addOption($this->i18n('lus_cleverreach_select_formid'), -1);
				foreach ($result->data as $dataset) {
					$selectformid->addOption($dataset->name, $dataset->id);
				}
				$htmlform[] = $selectformid->get();
			} else {
				$htmlform[] = $this->i18n('lus_cleverreach_formid_failure');
			}

		} catch (Exception $e) {
			$htmlform[] = $this->i18n('lus_cleverreach_api_failure');
		}
	}
}

$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', rex_i18n::msg('lus_cleverreach_info'), false);
$fragment->setVar('body', '<p>' . rex_i18n::msg('lus_cleverreach_infotext') . '</p>', false);
echo $fragment->parse('core/page/section.php');


$content = '<fieldset>';

$formElements = [];

$n = [];
$n['label'] = '<label for="apikey">' . $this->i18n('lus_cleverreach_apikey') . '</label>';
$n['field'] = '<input type="text" id="apikey" name="config[apikey]" size="40" value="' .$this->getConfig("apikey"). '" />';
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="groupid">' . $this->i18n('lus_cleverreach_groupid') . '</label>';
//$n['field'] = '<input type="text" id="groupid" name="config[groupid]" value="' .$this->getConfig('groupid'). '" />';
$n['field'] = implode('<br />',$htmlgroup);
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="formid">' . $this->i18n('lus_cleverreach_formid') . '</label>';
//$n['field'] = '<input type="text" id="formid" name="config[formid]" value="' . $this->getConfig('formid'). '" />';
$n['field'] = implode("\n",$htmlform);
$formElements[] = $n;

$n = [];
$n['label'] = '<label for="source">' . $this->i18n('lus_cleverreach_source') . '</label>';
$n['field'] = '<input type="text" id="source" name="config[source]" size="40" value="' .$this->getConfig('source'). '" />';
$formElements[] = $n;


$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/form.php');

$formElements = [];

$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="config-submit" value="1" ' . rex::getAccesskey($this->i18n('lus_cleverreach_config_save'), 'save') . '>' . $this->i18n('lus_cleverreach_config_save') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $this->i18n('lus_cleverreach_config'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$content = $fragment->parse('core/page/section.php');

echo '
    <form action="' . rex_url::currentBackendPage() . '" method="post">
        ' . $content . '
    </form>';
