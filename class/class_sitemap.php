<?php
class SitemapGenerator
{
    public static $document = null;
    private static $options = array();

    public function __construct($option = array())
    {
        if (isset($option)) {
            self::$options = $option;
            //Initialize DOMDocument class
            if (!self::$document) {
                self::$document = new DOMDocument(self::$options['version'], self::$options['charset']);
                self::$document->formatOutput = true;
                self::$document->preserveWhiteSpace = false;
            }
        } else {
            return 'Could not find option';
        }
    }


    public function generateXML($result)
    {
        $xml = $this->createElement('urlset');
        //Set the attributes.
        $xml->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->appendChild($xml);
        foreach ($result as $var) {
            $var['loc'] = $this->setURL($var['loc'], self::$options['data_url']);
            $var['lastmod'] = $this->trimLastmod($var['lastmod']);
            $item = $this->createElement('url');
            $xml->appendChild($item);
            $this->createItem($item, $var);
        }
        $this->saveFile(self::$options['xml_filename']);
        $this->saveXML();
    }

    private function setURL($get_url, $set_url)
    {
        return $set_url.$get_url;
    }

    private function trimLastmod($value)
    {
        return date('c', strtotime($value));
    }

    //Create element
    private function createElement($element)
    {
        return self::$document->createElement($element);
    }
    
    //Append child node
    private function appendChild($child)
    {
        return self::$document->appendChild($child);
    }
    
    //Add item
    private function createItem($item, $data, $attribute = array())
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                //Create an element, the element name cannot begin with a number
                is_numeric($key{0}) && exit($key.' Error: First char cannot be a number');
                $temp = self::$document->createElement($key);
                $item->appendChild($temp);
                //Add element value
                $text = self::$document->createTextNode($val);
                $temp->appendChild($text);
                if (isset($attribute[$key])) {
                    foreach ($attribute[$key] as $akey => $row) {
                        //Create attribute node
                        $temps = self::$document->createAttribute($akey);
                        $temp->appendChild($temps);
                        //Create attribute value node
                        $aval = self::$document->createTextNode($row);
                        $temps->appendChild($aval);
                    }
                } 
            }
        }
    }

    //Return xml string
    private function saveXML()
    {
        return self::$document->saveXML();
    }

    //Save xml file to path
    private function saveFile($fpath)
    {
        //Write file
        $writeXML = file_put_contents($fpath, self::$document->saveXML());
        if ($writeXML === true) {
            return self::$document->saveXML();
        } else {
            return 'Could not write into file';
        }
    }

    //Load xml file
    public function loadSitemap($fpath)
    {
        if (!file_exists($fpath)) {
            exit($fpath.' is a invalid file');
        }
        //Returns TRUE on success, or FALSE on failure
        self::$document->load($fpath);
        return print self::$document->saveXML();
    }
}
