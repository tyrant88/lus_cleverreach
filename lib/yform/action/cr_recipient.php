<?php

// ************************************* YFORM CR_RECIPIENT

class rex_yform_action_cr_recipient extends rex_yform_action_abstract
{

    function executeAction() :void
    {

        $addon = rex_addon::get('lus_cleverreach');
        if ( isset($addon) ) {
            $apikey = $addon->getConfig('apikey');
            $groupid = $addon->getConfig('groupid');
            $formid = $addon->getConfig('formid');
            $source = $addon->getConfig('source');
            $privacy = $addon->getConfig('privacy');
            $privacyitem = $addon->getConfig('privacyitem');
            $infotext = $addon->getConfig('infotext');
        }

        $error = false;
        $errormsg = '';
        if ( $this->getElement(5) != '' ) { $errormsg = $this->getElement(5); }

        // get post data
        foreach ($this->params['value_pool']['sql'] as $key => $value) {
            if ($this->getElement(2) == $key) {
                $email = $this->params['value_pool']['sql'][$key];
                break;
            }
        }

        $action = $this->getElement(3);
        if ( $action != '0' && $action != '1' ) {
            foreach ($this->params['value_pool']['sql'] as $key => $value) {
                if ($action == $key) {
                    $action = $value;
                    break;
                }
            }
        }

        $agreed = true; dump($this->getElement(6));
        if ($this->getElement(6) != '') {
            $checkboxfield = $this->getElement(6);
            $agreed = false;
            foreach ($this->params['value_pool']['email'] as $key => $value) {
                if ( $checkboxfield == $key ) {dump($value);
                    if ($value == 1) { $agreed = true; };
                }
            }
        }

        $attributes = array();
        if ($this->getElement(4) != '') {
            $fields = explode( ',',$this->getElement(4));

            foreach ($this->params['value_pool']['sql'] as $key => $value) {
                if (in_array($key, $fields)) {
                    $attributes[] = array('key'=>$key, 'value'=>$value);
                }
            }
        }

        if (!empty($email) && !empty($apikey) && !empty($groupid) && !empty($formid) && !$error && $agreed ) {

            // create Cleverreach API object
            $api = new CleverreachAPI($apikey);

            // define groupid
            $api->setGroupid($groupid);

            // define fromid
            $api->setFormid($formid);
            $errormsg = rex_i18n::msg('lus_cleverreach_api_failure');

            if ($action == "1") {
                // add recipient
                $result = $api->addRecipient($email, $source, $attributes);
            } elseif ($action == "0" ) {
                // remove recipient
                $result = $api->removeRecipient($email);
            } else {
                $errormsg = rex_i18n::msg('lus_cleverreach_add_remove');
            }

            if ($result->status === 'SUCCESS') {
                //$errormsg = rex_i18n::msg('lus_cleverreach_api_success');
            } else {
                $error = true;
                if ( $result->message != '' ) { $errormsg .= ': '. $result->message; }
            }
        } elseif (!empty($email) && $agreed && !$error) {
            $error == true;
            $errormsg = rex_i18n::msg('lus_cleverreach_config_failure');
        }

        if ( $error == true || empty($email)) {
            $this->params['form_show'] = true;
            $this->params['hasWarnings'] = true;
            $this->params['warning_messages'][] = $errormsg;
            $this->params['warning'][$this->getId()] = $this->params['error_class'];
        }

    }

    function getDescription() :string
    {
        return 'cr_recipient -> Beispiel: action|cr_recipient|emailfield|0/1/actionfield|anrede,titel,vorname,nachname,firma|errormsg|checkboxfeld';
    }

}
