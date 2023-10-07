<?php
namespace carry0987\Sitemap;

class DBController
{
    private $connectDB = null;

    public function connectDB(string $host, string $user, string $password, string $database, int $port = 3306)
    {
        try {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            //Handle Exception of MySQLi
            $driver = new \mysqli_driver();
            $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
            $this->connectDB = new \mysqli($host, $user, $password, $database, $port);
            $this->mysqli_version = $this->connectDB->server_info;
            $this->connectDB->set_charset('utf8mb4');
            return $this->connectDB;
        } catch (\mysqli_sql_exception $e) {
            echo $this->throwDBError($e->getMessage(), $e->getCode());
            exit();
        }
    }

    public function setConnection(\mysqli $connectDB)
    {
        $this->connectDB = $connectDB;
    }

    public function getConnection()
    {
        return $this->connectDB;
    }

    public function getArticle()
    {
        $results = array();
        $query = 'SELECT id, freq, priority, lastmod FROM article';
        $stmt = $this->connectDB->stmt_init();
        try {
            $stmt->prepare($query);
            $stmt->execute();
            $stmt->bind_result($id, $freq, $priority, $lastmod);
            $result = $stmt->get_result();
            if ($result->num_rows != 0) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = array(
                        //Just enter the url, it will escape specific word automatically
                        'loc' => 'https://example.com/article.php?test=yes&aid='.$row['id'],
                        'lastmod' => $row['lastmod'],
                        'changefreq' => $row['freq'],
                        'priority' => $row['priority']
                    );
                }
            }
            return $results;
        } catch (\mysqli_sql_exception $e) {
            echo self::throwDBError($e->getMessage(), $e->getCode());
            return false;
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
