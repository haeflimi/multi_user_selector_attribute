<?php

namespace Concrete\Package\UserSelectorAttribute\Attribute\UserSelector;

use Concrete\Core\Attribute\Controller as AttributeTypeController;
use Core;
use Page;
use Database;

class Controller extends AttributeTypeController
{
    public $helpers = array('form');

    protected $searchIndexFieldDefinition = array(
        'pageID' => array(
            'type' => 'integer',
            'options' => array('length' => '11', 'default' => null, 'notnull' => false),
        )
    );

    public function deleteKey()
    {
        $db = Database::connection();
        $arr = $this->attributeKey->getAttributeValueIDList();
        foreach ($arr as $id) {
            $sql = 'DELETE ps '
                . 'FROM atPageSelector AS ps '
                . 'WHERE ps.avID = ?';
            $db->Execute($sql, array($id));
        }
    }

    public function deleteValue()
    {
        $db = Database::connection();
        $sql = 'DELETE ps '
                . 'FROM atPageSelector AS ps '
                . 'WHERE ps.avID = ?';
        $db->Execute($sql, array($this->getAttributeValueID()));
    }

    public function exportKey($akey)
    {
        return $akey;
    }

    public function exportValue(\SimpleXMLElement $akn)
    {
        $avn = $akn->addChild('value');
        $pageID = $this->getValue();
        $avn->addAttribute('pageID', $pageID);
    }

    public function form()
    {
        if (is_object($this->attributeValue)) {
            $value = $this->getAttributeValue()->getValue();
            if (is_object($value)) {
                $this->set('pageID', $value->getPageID());
            }
        }
        $this->set('key', $this->attributeKey);
    }

    public function getDisplayValue()
    {
        $v = Core::make('helper/text')->entities($this->getValue());
        $ret = nl2br($v);
        return $ret;
    }

    public function getSearchIndexValue()
    {
        $v = $this->getValue();
        $args = array();
        $args['pageID'] = is_object($v) ? $v->getPageID() : null;
        return $args;
    }

    public function getValue()
    {
        $val = Value::getByID($this->getAttributeValueID());
        return $val;
    }
    
    public function getValueIDsByPageID($cID)
    {
        $db = Database::connection();
        $sql = 'SELECT avID FROM atPageSelector WHERE pageID = ?';
        return $db->GetCol($sql, array($cID));
    }

    public function importValue(\SimpleXMLElement $akv)
    {
        if (isset($akv->value)) {
            $data['pageID'] = $akv->value['pageID'];
            return $data;
        }
    }

    public function saveForm($data)
    {
        $this->saveValue($data);
    }

    public function saveValue($data)
    {
        $db = Database::connection();
        $pageID = $data['value'];
        $page = Page::getByID($pageID);
        if (is_object($page) && !$page->isError()) {
            $db->Replace(
                'atPageSelector',
                array(
                    'avID' => $this->getAttributeValueID(),
                    'pageID' => $pageID
                ),
                'avID',
                true
            );
        }
    }

    public function search()
    {
        print $this->form();
        $v = $this->getView();
        $this->set('search', true);
        $v->render('form');
    }

    public function searchForm($list)
    {
        return $list;
    }

    public function searchKeywords($keywords, $queryBuilder)
    {
        return $queryBuilder;
    }

    public function validateForm($data)
    {
        if (isset($data['pageID'])) {
            $c = Page::getByID($data['pageID']);
            return is_object($c) && !$c->isError();
        } else {
            return false;
        }
    }

    public function validateValue()
    {
        $v = $this->getValue();
        if (!is_object($v)) {
            return false;
        }
        if (trim((string) $v) == '') {
            return false;
        }

        return true;
    }
}
