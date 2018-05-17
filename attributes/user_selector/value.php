<?php
namespace Concrete\Package\MeschPageSelector\Attribute\MeschPageSelector;

use Loader;
use Page;
use \Concrete\Core\Foundation\Object;

class Value extends Object
{
    public static function getByID($avID)
    {
        $db = Loader::db();
        $value = $db->GetRow(
            "select avID, pageID from atPageSelector where avID = ?",
            array($avID)
        );
        $aa = new Value();
        $aa->setPropertiesFromArray($value);
        if ($value['avID']) {
            return $aa;
        }
    }

    public function __construct()
    {

    }

    public function getPageID()
    {
        $page = Page::getByID($this->pageID);
        return is_object($page) && !$page->isError() ? $this->pageID : null;
    }

    public function getPage()
    {
        $page = Page::getByID($this->getPageID());
        return is_object($page) && !$page->isError() ? $page : null;
    }

    public function getURL()
    {
        $page = $this->getPage();
        return is_object($page) && !$page->isError() ? $page->getCollectionLink() : '';
    }

    public function __toString()
    {
        $page = Page::getByID($this->getPageID());
        return $this->getPageID() . ' - ' . $page->getCollectionName();
    }
}