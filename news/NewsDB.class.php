<?php
require_once 'INewsDB.class.php';

class NewDb implements INewsDB{
    protected $_db;
    const DB_NAME = "C:\OpenServer\domains\level.three\\news.db";

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
}

?>