<?php
require dirname(__DIR__).'/vendor/autoload.php';
use carry0987\Sitemap\SitemapGenerator as SitemapGenerator;
use carry0987\Sitemap\DBController as DBController;

//Connect to database
$db = new DBController;
$db->connectDB('localhost', 'root', '1234', 'sitemap');
//Get article data
$article = $db->getArticle();

//Add another data, and it will merge into sitemap.xml file
$category[] = array(
    //Just enter the url, it will escape specific word automatically
    'loc' => 'http://example.com/?test=yes&cid=10',
    'lastmod' => '2019-05-12 10:47:05',
    'changefreq' => 'always', 
    'priority' => '0.8'
);
$board[] = array(
    //Just enter the url, it will escape specific word automatically
    'loc' => 'http://example.com/?test=yes&bid=3',
    'lastmod' => '2019-05-12 10:47:05',
    'changefreq' => 'always', 
    'priority' => '1.0'
);

//Set Sitemap
$sitemapOption = array(
    'version' => '1.0',
    'charset' => 'UTF-8',
    'xml_file' => dirname(__FILE__).'\sitemap.xml'
);
$sitemap = new SitemapGenerator($sitemapOption);
$sitemap->addSitemapNode($category);
$sitemap->addSitemapNode($board);
$sitemap->addSitemapNode($article);
$sitemap->generateXML();

// Set the content type to be XML, so that the browser will recognise it as XML
header('content-type: application/xml; charset=UTF-8');
//Load xml content
$sitemap->loadSitemap($sitemapOption['xml_file']);
