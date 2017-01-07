<?php 

namespace App\Repository;

use PDO;
use App\Models\Image;
use App\Models\Tag;

class ImageRepository extends Repository
{
    public function find($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Image", images.* 
             FROM images 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Image::class);
        return $stmt->fetch();
    }
	
    public function findAll()
    {
        $stmt = $this->getConnection()->prepare('
            SELECT * FROM images
        ');
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, Image::class);
        

        return $stmt->fetchAll();
    }
    public function save(Image $image)
    {
        // If the ID is set, we're updating an existing record
        if (isset($image->id)) {
            return $this->update($image);
        }
        $stmt = $this->getConnection()->prepare('
            INSERT INTO images 
                (name, url, width, height) 
            VALUES 
                (:name, :url, :width , :height)
        ');
        $stmt->bindParam(':name', $image->name);
        $stmt->bindParam(':url', $image->url);
        $stmt->bindParam(':width', $image->width);
        $stmt->bindParam(':height', $image->height);
        $stmt->execute();
        $image->id = $this->getConnection()->lastInsertId();
        return $image;
    }
    public function update(Image $image)
    {
        if (!isset($image->id)) {
            // We can't update a record unless it exists...
            throw new \LogicException(
                'Cannot update image that does not yet exist in the database.'
            );
        }
        $stmt = $this->getConnection()->prepare('
            UPDATE images
            SET name= :name,
            	url = :url,
                width = :width,
                height = :height
            WHERE id = :id
        ');
        $stmt->bindParam(':name', $image->name);
        $stmt->bindParam(':url', $image->url);
        $stmt->bindParam(':width', $image->width);
        $stmt->bindParam(':height', $image->height);
        $stmt->bindParam(':id', $image->id);
        $stmt->execute();
        return $image;
    }
    public function delete($id)
    {
        $stmt = $this->getConnection()->prepare('
            DELETE FROM images 
             WHERE id = :id
        ');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    public function getTags($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Tags", tags.tag
            FROM tags 
            INNER JOIN images_tags ON tags.id = images_tags.tag_id
            WHERE images_tags.image_id = :id
        ');

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Tag::class);
        return $stmt->fetchAll();    	
    }
    public function updateTags($id, $tags)
    {
    	$stmt = $this->getConnection()->prepare('
            DELETE FROM images_tags 
             WHERE image_id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $values = implode(',',array_map(function($tag) use ($id){ return "($id,$tag)"; },$tags));
        if($values){
	    	$stmt = $this->getConnection()->prepare('
	            INSERT INTO images_tags  (image_id, tag_id)
	            VALUES '.$values);
	        return $stmt->execute();     
	    }
	    else  
	    	return true;
    }
    public function search($tags, $width = 0, $height = 0, $count = 0, $match_all = true)
    {
    	$tags = explode(",",$tags);
    	$tags = array_map('trim',$tags);
    	$tags = array_map('strtolower',$tags);
    	$tags = array_unique($tags);
    	$c_tags = count($tags);
    	$tags = "'".implode("','",$tags)."'";
    	$where = $width ? " AND images.width=:width " : "";
    	$where .= $height ? " AND images.height=:height " : "";

    	$query = 'SELECT images.* FROM images
            INNER JOIN images_tags ON images.id = images_tags.image_id 
            INNER JOIN tags ON tags.id = images_tags.tag_id
            WHERE tags.tag in ('.$tags.') '. $where .' 
            GROUP BY images.id ';
        if($match_all)
            $query .= 'HAVING count(images.id) = :c_tags ';
        $query .= 'ORDER BY count(images.id) DESC ';
        if($count){
            $query .= 'LIMIT :count';
        }
		$stmt = $this->getConnection()->prepare($query);

        if($match_all)
            $stmt->bindParam(':c_tags', $c_tags);
        if($count)
            $stmt->bindValue(':count', (int)$count, PDO::PARAM_INT);
        if($width)
            $stmt->bindValue(':width', (int)$width, PDO::PARAM_INT);
        if($height)
            $stmt->bindValue(':height', (int)$height, PDO::PARAM_INT);        
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Image::class);
        return $stmt->fetchAll();   	
    }
}