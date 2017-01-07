<?php 

namespace App\Repository;

use PDO;
use App\Models\Tag;

class TagRepository extends Repository
{
    public function find($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Tag", tags.* 
             FROM tags 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Tag::class);
        return $stmt->fetch();
    }
    public function findByTag($tag)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Tag", tags.* 
             FROM tags 
             WHERE tag = :tag
        ');
        $stmt->bindParam(':tag', $tag);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Tag::class);
        return $stmt->fetch();
    }    
	
    public function findAll()
    {
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM tags
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Tag::class);
        

        return $stmt->fetchAll();
    }
    public function save(Tag $tag)
    {
        // If the ID is set, we're updating an existing record
        if (isset($tag->id)) {
            return $this->update($tag);
        }
        $stmt = $this->getConnection()->prepare('
            INSERT INTO tags 
                (tag) 
            VALUES 
                (:tag)
        ');
        $stmt->bindParam(':tag', $tag->tag);
        $stmt->execute();
        $tag->id = $this->getConnection()->lastInsertId();
        return $tag;
    }
    public function update(Tag $tag)
    {
        if (!isset($tag->id)) {
            // We can't update a record unless it exists...
            throw new \LogicException(
                'Cannot update tag that does not yet exist in the database.'
            );
        }
        $stmt = $this->getConnection()->prepare('
            UPDATE tags
            SET tag= :tag,
            WHERE id = :id
        ');
        $stmt->bindParam(':tag', $tag->tag);
        $stmt->bindParam(':id', $tag->id);
        $stmt->execute();
        return $tag;
    }
    public function delete($id)
    {
        $stmt = $this->getConnection()->prepare('
            DELETE FROM tags 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function searchByTags($tags)
    {
    	$tags = explode(",",$tags);
    	$tags = array_map('trim',$tags);
        $tags = array_map('strtolower',$tags);
        $result = array();
        foreach($tags as $tag_name)
    	{
            if(!$tag_name)
                continue;
            $tag = $this->findByTag($tag_name);
            if(!isset($tag->id)){
                $tag = new Tag(['tag'=>$tag_name]);
                $tag = $this->save($tag);
            }
            $result[$tag_name] = $tag->id;
        }
        return $result; 	
    }
}