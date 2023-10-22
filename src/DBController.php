<?php
namespace carry0987\Sitemap;

use PDO;
use PDOException;

class DBController
{
    private $PDO = null;

    public function connectDB(string $host, string $user, string $password, string $database, int $port = 3306)
    {
        $dsn = "mysql:host=$host;dbname=$database;port=$port;charset=utf8mb4";

        try {
            $this->PDO = new PDO($dsn, $user, $password);
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo self::throwDBError($e->getMessage(), $e->getCode());
            exit();
        }
    }

    public function setConnection(PDO $PDO)
    {
        $this->PDO = $PDO;
    }

    public function getConnection()
    {
        return $this->PDO;
    }

    public function getArticle(): array
    {
        $query = 'SELECT id, freq, priority, lastmod FROM article';

        try {
            $stmt = $this->PDO->query($query);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = null; // closes the cursor and frees the connection to the server so that other SQL statements may be issued,

            foreach ($results as $key => $row) {
                //Just enter the url, it will escape specific word automatically
                $results[$key]['loc'] = 'https://example.com/article.php?test=yes&aid='.$row['id'];
                $results[$key]['lastmod'] = $row['lastmod'];
                $results[$key]['changefreq'] = $row['freq'];
                $results[$key]['priority'] = $row['priority'];
            }
    
            return $results;
        } catch (PDOException $e) {
            echo self::throwDBError($e->getMessage(), $e->getCode());
            return [];
        }
    }

    //Throw database error excetpion
    private static function throwDBError(string $message, int $code)
    {
        $error = '<h1>Service unavailable</h1>'."\n";
        $error .= '<h2>Error Info :'.$message.'</h2>'."\n";
        $error .= '<h3>Error Code :'.$code.'</h3>'."\n";
        return $error;
    }
}
