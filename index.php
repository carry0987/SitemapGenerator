<?php
require 'class/class_sitemap.php';
// Set the content type to be XML, so that the browser will recognise it as XML
header('content-type: application/xml; charset=UTF-8');
//Connect to database
$conn = new mysqli('localhost', 'root', '', 'article');
$conn->query('SET CHARACTER SET utf8');
$article_query = 'SELECT id,freq,priority,lastmod FROM article';
$article_stmt = $conn->stmt_init();
//Get result
$article_stmt->prepare($article_query);
$article_stmt->execute();
$article_stmt->bind_result($id, $freq, $priority, $lastmod);
$article_result = $article_stmt->get_result();
if ($article_result->num_rows != 0) {
    while ($article_row = $article_result->fetch_assoc()) {
        $article[] = array(
            //Just enter the url, it will escape specific word automatically
            'loc' => 'https://example.com/article.php?test=yes&aid='.$article_row['id'],
            'lastmod' => $article_row['lastmod'],
            'changefreq' => $article_row['freq'],
            'priority' => $article_row['priority']
        );
    }
}
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
    'xml_filename' => 'sitemap.xml'
);
$sitemap = new SitemapGenerator($sitemapOption);
$sitemap->addSitemapNode($category);
$sitemap->addSitemapNode($board);
$sitemap->addSitemapNode($article);
$sitemap->generateXML();
//Load xml content
$sitemap->loadSitemap($sitemapOption['xml_filename']);
