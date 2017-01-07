<?php $this->extend('layout')->block('main.content'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2>Image : <?php echo $image->name; ?></h2>
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Name:</strong> <?php echo $image->name; ?>
                    </p>                
                    <p>
                        <strong>Url:</strong> <?php echo $image->url; ?>
                        <img src="<?php echo $image->url; ?>" class="img-responsive" style="height:300px">
                    </p>
                    <p>
                        <strong>Width:</strong> <?php echo $image->width; ?>
                    </p>
                    <p>
                        <strong>Height:</strong> <?php echo $image->height; ?>
                    </p>  
                    <p>
                        <strong>Tags:</strong> 
                        <?php 
                            echo implode(", ", array_map(function($tag) { return $tag->tag; }, $image->tags));
                        ?>
                    </p>                       
                    <p>                        
                        <a class="btn btn-primary" href="/image/<?php echo $image->id; ?>/edit">Edit</a>
                        <a class="btn btn-warning" href="/image">Cancel</a>
                    </p>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock('main.content'); ?>


