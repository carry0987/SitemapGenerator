<?php
namespace carry0987\Sitemap;

class SitemapGenerator
{
    public static $document = null;
    private static $options = array();
    const DIR_SEP = DIRECTORY_SEPARATOR;

    public function __construct($option = array())
    {
        if (isset($option)) {
            self::$options = $option;
            if (!self::$document) {
                self::$document = new \DOMDocument(self::$options['version'], self::$options['charset']);
                self::$document->formatOutput = true;
                self::$document->preserveWhiteSpace = false;
                //Generate the urlset once
                $this->addUrlset();
            }
        } else {
            throw new \InvalidArgumentException('Could not find option');
        }
    }

    //Generate the root node - urlset
    private function addUrlset()
    {
        $urlset = $this->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->appendChild($urlset);
    }

    //Add item to xml
    public function addSitemapNode(array $result = array())
    {
        if (!empty($result)) {
            foreach ($result as $var) {
                if (!isset($var['loc']) || !isset($var['lastmod'])) {
                    throw new \InvalidArgumentException('Missing loc or lastmod key in result array');
                }
                $get_urlset = self::$document->getElementsByTagName('urlset');
                $urlset = $get_urlset[0];
                $var['loc'] = htmlentities($var['loc']);
                $var['lastmod'] = $this->trimLastMod($var['lastmod']);
                $item = $this->createElement('url');
                $urlset->appendChild($item);
                $this->createItem($item, $var);
            }
        } else {
            throw new \InvalidArgumentException('Invalid result array provided');
        }
    }

    private function trimLastMod($value)
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
    private function createItem($item, array $data, $attribute = array())
    {
        foreach ($data as $key => $val) {
            //Create an element, the element name cannot begin with a number
            is_numeric($key[0]) && exit($key.' Error: First char cannot be a number');
            $temp = self::$document->createElement($key);
            $item->appendChild($temp);
            //Add element value
            $text = self::$document->createTextNode($val);
            $temp->appendChild($text);
            if (isset($attribute[$key])) {
                if (!is_array($attribute[$key])) {
                    throw new \InvalidArgumentException('Attributes must be passed in an array');
                }
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

    private static function trimPath($path)
    {
        return str_replace(array('/', '\\', '//', '\\\\'), self::DIR_SEP, $path);
    }

    //Generate XML file
    public function generateXML()
    {
        $file_path = self::trimPath(self::$options['xml_file']);
        if ($this->saveFile($file_path)) {
            $this->saveXML();
            return true;
        } else {
            throw new \Exception('Failed to save file');
        }
    }

    //Load xml file
    public function loadSitemap($fpath)
    {
        $fpath = self::trimPath($fpath);
        if (!file_exists($fpath)) {
            throw new \Exception($fpath.' is a invalid file');
        }
        //Returns TRUE on success, or FALSE on failure
        self::$document->load($fpath);
        return print self::$document->saveXML();
    }
}
