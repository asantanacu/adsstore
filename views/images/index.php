<?php $this->extend('layout')->block('main.content'); ?>
            
<div class="container">
    <div class="row">
	    <div class="panel panel-default">
		    <div class="panel-heading">
		        <h1>
		            Images
		            <a href="/image/create">
		              <span class="btn btn-lg glyphicon glyphicon-plus-sign"></span>
		            </a>
		        </h1>
		    </div>
			<div class="panel-body">
			    <table class="table table-striped">
			        <thead>
			            <tr>
			                <td>ID</td>
			                <td>Name</td>
			                <td>URL</td>
			                <td>Width</td>
			                <td>Height</td>
			                <td>Actions</td>
			            </tr>
			        </thead>
			        <tbody>
			        <?php foreach($images as $image) {?>
			            <tr>
			                <td><?php echo $image->id; ?></td>
			                <td><?php echo $image->name; ?></td>
			                <td><?php echo $image->url; ?></td>
			                <td><?php echo $image->width; ?></td>
			                <td><?php echo $image->height; ?></td>
			                <td>
			                	<form action="/image/<?php echo $image->id; ?>" method="POST" class="form-inline">
			                        <a href="/image/<?php echo $image->id; ?>" >
			                              <span class="btn btn-xs btn-success glyphicon glyphicon-eye-open"></span>
			                        </a>
			                        <a href="/image/<?php echo $image->id; ?>/edit">
			                              <span class="btn btn-xs btn-info glyphicon glyphicon-pencil"></span>
			                        </a>             
			                        <input type="hidden" name="_method" value="DELETE">       

			                        <button class="btn btn-warning btn-xs glyphicon glyphicon-trash" type="submit" ></button>
			                    </form>
			                </td>
			            </tr>
			        <?php } ?>
			        </tbody>
			    </table>
			</div>
		</div>
	</div>
</div>
<?php $this->endBlock('main.content'); ?>


