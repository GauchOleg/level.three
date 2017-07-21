<?php
require_once 'INewsDB.class.php';

class NewDb implements INewsDB{
    protected $_db;
    const DB_NAME = "C:\OpenServer\domains\level.three\\news.db";
    const RSS_NAME = 'rss.xml';
    const RSS_TITLE = 'Последние новости';
    const RSS_LINK = 'level.three/news/news.php';

    function __construct()
    {
        try{
            if (is_file(self::DB_NAME)){
                $this->_db = new SQLite3(self::DB_NAME);
            }else{
                $this->_db = new SQLite3(self::DB_NAME);
                $sql = "CREATE TABLE msgs(
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    title TEXT,
                    category INTEGER,
                    description TEXT,
                    source TEXT,
                    datetime INTEGER
                    )";
                $res = $this->_db->exec($sql);
                if (!$res){
                    throw new Exception($this->_db->lastErrorMsg());
                }
                $sql = "CREATE TABLE category(
                    id INTEGER,
                    name TEXT
                    )";
                $res = $this->_db->exec($sql);
                if (!$res){
                    throw new Exception($this->_db->lastErrorMsg());
                }
                $sql = "INSERT INTO category(id, name)
                    SELECT 1 as id, 'Политика' as name
                    UNION SELECT 2 as id, 'Культура' as name
                    UNION SELECT 3 as id, 'Спорт' as name";
                $res = $this->_db->exec($sql);
                if (!$res){
                    throw new Exception($this->_db->lastErrorMsg());
                }
            }
        }catch (Exception $e){
            $e->getMessage();
            return false;
        }
    }

    function __destruct()
    {
        unset($this->_db);
    }

    function clearStr($data){
        $str = trim(strip_tags($data));
        return $this->_db->escapeString($str);
    }

    function clearInt($data){
        return abs((int)$data);
    }

    function saveNews($title, $category, $description, $source)
    {
        try{
            $dt = time();
            $sql = "INSERT INTO msgs (title, category, description, source, datetime) 
                VALUES ('$title', $category, '$description', '$source', $dt)";
            $result = $this->_db->exec($sql) or die($this->_db->lastErrorMsg());
            if (!$result){
                throw new Exception($this->_db->lastErrorMsg());
            }
            $this->createRss();
            return true;
        }catch (Exception $e){
            $e->getMessage();
            return false;
        }

    }

    protected function db2Arr($data)
    {
        $arr = array();
        while ($row = $data->fetchArray(SQLITE3_ASSOC)){
            $arr[] = $row;
        }
        return $arr;
    }

    function getNews()
    {
        try{
            $sql = "SELECT msgs.id as id, title, category.name as category, description, source, datetime 
        FROM msgs, category
        WHERE category.id = msgs.category
        ORDER BY msgs.id DESC";
            $result = $this->_db->query($sql);
            if (!is_object($result)){
                throw new Exception($this->_db->lastErrorMsg());
            }
            return $this->db2Arr($result);
        }catch (Exception $e){
            $e->getMessage();
            return false;
        }
    }

    function deleteNews($id)
    {
        try{
            $sql = "DELETE FROM msgs WHERE id=$id";
            $res = $this->_db->exec($sql) or die($this->_db->lastErrorMsg());
            if (!$res){
                throw new Exception($this->_db->lastErrorMsg());
            }
            return true;
        }catch (Exception $e){
            $e->getMessage();
            return false;
        }
    }

    function createRss(){
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;
        $rss = $dom->createElement('rss');
        $version = $dom->createAttribute('version');
        $version->value = '2.0';
        $rss->appendChild($version);
        $dom->appendChild($rss);
        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);
        $title = $dom->createElement('title', self::RSS_NAME);
        $link = $dom->createElement('link', self::RSS_LINK);
        $channel->appendChild($title);
        $channel->appendChild($link);
        $lenta = $this->getNews();
        if(!$lenta) return false;
        foreach ($lenta as $news){
            $item = $dom->createElement('item');
            $title = $dom->createElement('title',$news['title']);
            $category = $dom->createElement('category',$news['category']);
            $description = $dom->createElement('description',$news['description']);
            $txt = self::RSS_LINK . '?id=' . $news['id'];
            $link = $dom->createElement('link',$txt);
            $dt = date('r', $news['datetime']);
            $pd = $dom->createElement('pubDate',$dt);
            $item->appendChild($title);
            $item->appendChild($link);
            $item->appendChild($description);
            $item->appendChild($pd);
            $item->appendChild($category);
            $item->appendChild($category);
            $channel->appendChild($item);
        }
        $dom->save(self::RSS_NAME);
    }
}

?>