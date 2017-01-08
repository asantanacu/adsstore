<?php 

namespace App\Repository;

use PDO;
use App\Models\Image;
use App\Models\Keyword;

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
    public function getKeywords($id)
    {
        $stmt = $this->getConnection()->prepare('
            SELECT "Keywords", keywords.keyword
            FROM keywords 
            INNER JOIN images_keywords ON keywords.id = images_keywords.keyword_id
            WHERE images_keywords.image_id = :id
        ');

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, Keyword::class);
        return $stmt->fetchAll();    	
    }
    public function updateKeywords($id, $keywords)
    {
    	$stmt = $this->getConnection()->prepare('
            DELETE FROM images_keywords 
             WHERE image_id = :id
        ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $values = implode(',',array_map(function($keyword) use ($id){ return "($id,$keyword)"; },$keywords));
        if($values){
	    	$stmt = $this->getConnection()->prepare('
	            INSERT INTO images_keywords  (image_id, keyword_id)
	            VALUES '.$values);
	        return $stmt->execute();     
	    }
	    else  
	    	return true;
    }
    public function search($keywords, $width = 0, $height = 0, $count = 0, $match_all = true)
    {
    	$keywords = explode(",",$keywords);
    	$keywords = array_map('trim',$keywords);
    	$keywords = array_map('strtolower',$keywords);
    	$keywords = array_unique($keywords);
    	$c_keywords = count($keywords);
    	$keywords = "'".implode("','",$keywords)."'";
    	$where = $width ? " AND images.width=:width " : "";
    	$where .= $height ? " AND images.height=:height " : "";

    	$query = 'SELECT images.* FROM images
            INNER JOIN images_keywords ON images.id = images_keywords.image_id 
            INNER JOIN keywords ON keywords.id = images_keywords.keyword_id
            WHERE keywords.keyword in ('.$keywords.') '. $where .' 
            GROUP BY images.id ';
        if($match_all)
            $query .= 'HAVING count(images.id) = :c_keywords ';
        $query .= 'ORDER BY count(images.id) DESC ';
        if($count){
            $query .= 'LIMIT :count';
        }
		$stmt = $this->getConnection()->prepare($query);

        if($match_all)
            $stmt->bindParam(':c_keywords', $c_keywords);
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