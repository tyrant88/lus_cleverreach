<?php
/**
 * @copyright    Copyright (C) Matic-Tec.de. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

class CleverreachAPI
{
    protected $apikey;
    protected $groupid;
    protected $formid;
    protected $api;
    protected $errors;

    public function setApikey($apikey)
    {
        $this->apikey = $apikey;
    }

    public function getApikey()
    {
        return $this->apikey;
    }

    public function setGroupid($groupid)
    {
        $this->groupid = $groupid;
    }

    public function getGroupid()
    {
        return $this->groupid;
    }

    public function setFormid($formid)
    {
        $this->formid = $formid;
    }

    public function getFormid()
    {
        return $this->formid;
    }

    public function setApi($api)
    {
        $this->api = $api;
    }

    public function getApi()
    {
        return $this->api;
    }

    public function __construct($apikey = null)
    {
        if (!empty($apikey)) {
            $this->setApikey(trim($apikey));
        }

        $api = $this->getApi();


        if (empty($api)) {
            try {
                $this->setApi(new SoapClient('http://api.cleverreach.com/soap/interface_v5.1.php?wsdl'));
            } catch (Exception $e) {

            }
        }
    }

    public function getGroupList()
    {
        $api = $this->getApi();

        if (!empty($api)) {
            $result = false;
            try {
                $result = $api->groupGetList($this->getApikey());
            } catch (Exception $e) {

            }
            return $result;
        } else {
            return false;
        }
    }

    public function getFormsList($groupid)
    {
        $api = $this->getApi();

        if (!empty($api) && !empty($groupid)) {
            $result = false;
            try {
                $result = $api->formsGetList($this->getApikey(), $groupid);
            } catch (Exception $e) {

            }
            return $result;
        } else {
            return false;
        }
    }

    public function addRecipient($email, $source, $attr = array())
    {
        $api = $this->getApi();
        $groupid = $this->getGroupid();
        $formid = $this->getFormid();

        // create user
        $user = array(
            "email" => $email,
            "registered" => time(),
            "activated" => 0,
            "source" => $source,
            "active" => false,
            "attributes" => $attr
        );

        if (!empty($api) && !empty($groupid) && !empty($formid)) {
            $result = false;
            try {
                $result = $api->receiverAdd($this->getApikey(), $groupid, $user);
                if ($result->status === 'SUCCESS'){
                    $result = $api->formsActivationMail($this->getApikey(), $formid, $email);
                } else {
                    return $result;
                }
            } catch (Exception $e) {

            }
            return $result;
        } else {
            return false;
        }
    }

    public function removeRecipient($email)
    {
        $api = $this->getApi();
        $groupid = $this->getGroupid();
        $formid = $this->getFormid();

        if (!empty($api) && !empty($groupid) && !empty($formid)) {
            $result = false;
            try {
                $result = $api->receiverDelete($this->getApikey(), $groupid, $email);
                //$result = $api->formsSendUnsubscribeMail($this->getApikey(), $formid, $email);
                if ($result->status === 'SUCCESS'){

                } else {
                    return $result;
                }
            } catch (Exception $e) {

            }
            return $result;
        } else {
            return false;
        }
    }
}