<?php $this->extend('layout')->block('main.content'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php if(isset($image)) { ?>
                        <h2>Edit Image: <i class="text-primary"><?php echo $image->name; ?></i></h2>
                    <?php }else{ ?>
                        <h2>Add new Image</h2>
                    <?php } ?>
                </div>
                <div class="panel-body">

                    <?php if(isset($image)) { ?>
                    	<form action="/image/<?php echo $image->id; ?>" class="form-horizontal" method="POST" >
                    	    <input type="hidden" name="_method" value="PUT" >
                    <?php }else{ ?>
                        <form action="/image" class="form-horizontal" method="POST" >
                    <?php } ?> 

                    <div class="form-group">
                        
                        <label for="name" class="col-md-4 control-label">Name</label>

                        <div class="col-md-6">
                        	<input type="text" name="name" class="form-control" required="required" value="<?php echo isset($image) ? $image->name : '' ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <label for="url" class="col-md-4 control-label">Url</label>

                        <div class="col-md-6">
                        	<input type="text" name="url" class="form-control" required="required" value="<?php echo isset($image) ? $image->url : '' ?>" >
                            <br/><img src="<?php echo $image->url; ?>" class="img-responsive" style="height:300px">
                        </div>

                    </div>

                    <div class="form-group">
                        
                        <label for="width" class="col-md-4 control-label">Width</label>

                        <div class="col-md-6">
                        	<input type="number" name="width" class="form-control" required="required" value="<?php echo isset($image) ? $image->width : '' ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <label for="height" class="col-md-4 control-label">Height</label>

                        <div class="col-md-6">
                        	<input type="number" name="height" class="form-control" required="required" value="<?php echo isset($image) ? $image->height : '' ?>" >
                        </div>
                    </div>

                    <div class="form-group">
                        
                        <label for="height" class="col-md-4 control-label">Keywords</label>

                        <div class="col-md-6">
                        	<input type="text" name="keywords" class="form-control"
                        	value="<?php echo isset($image) ? implode(", ", array_map(function($keyword) { return $keyword->keyword; }, $image->keywords)) : '' ?>" >
                        </div>
                    </div>   

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                                <input type="submit" class="btn btn-primary" value="Save Image!" >
                                <a class="btn btn-warning" href="/image">Cancel</a>
                        </div>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endBlock('main.content'); ?>