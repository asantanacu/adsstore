<?php 

namespace App\Repository;

use PDO;
use App\Models\Keyword;

class KeywordRepository extends Repository
{
    public function find($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Keyword", keywords.* 
             FROM keywords 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Keyword::class);
        return $stmt->fetch();
    }
    public function findByKeyword($keyword)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Keyword", keywords.* 
             FROM keywords 
             WHERE name = :name
        ');
        $stmt->bindParam(':name', $keyword);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Keyword::class);
        return $stmt->fetch();
    }    
	
    public function findAll()
    {
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM keywords
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Keyword::class);
        

        return $stmt->fetchAll();
    }
    public function save(Keyword $keyword)
    {
        // If the ID is set, we're updating an existing record
        if (isset($keyword->id)) {
            return $this->update($keyword);
        }
        $stmt = $this->getConnection()->prepare('
            INSERT INTO keywords 
                (name) 
            VALUES 
                (:name)
        ');
        $stmt->bindParam(':name', $keyword->name);
        $stmt->execute();
        $keyword->id = $this->getConnection()->lastInsertId();
        return $keyword;
    }
    public function update(Keyword $keyword)
    {
        if (!isset($keyword->id)) {
            // We can't update a record unless it exists...
            throw new \LogicException(
                'Cannot update keyword that does not yet exist in the database.'
            );
        }
        $stmt = $this->getConnection()->prepare('
            UPDATE keywords
            SET name= :name,
            WHERE id = :id
        ');
        $stmt->bindParam(':name', $keyword->name);
        $stmt->bindParam(':id', $keyword->id);
        $stmt->execute();
        return $keyword;
    }
    public function delete($id)
    {
        $stmt = $this->getConnection()->prepare('
            DELETE FROM keywords 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function searchByKeywords($keywords)
    {
    	$keywords = explode(",",$keywords);
    	$keywords = array_map('trim',$keywords);
        $keywords = array_map('strtolower',$keywords);
        $result = array();
        foreach($keywords as $name)
    	{
            if(!$name)
                continue;
            $keyword = $this->findByKeyword($name);
            if(!isset($keyword->id)){
                $keyword = new Keyword(['name'=>$name]);
                $keyword = $this->save($keyword);
            }
            $result[$name] = $keyword->id;
        }
        return $result; 	
    }
}