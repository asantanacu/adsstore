<?php $this->extend('layout')->block('main.content'); ?>
            
<div class="container">
    <div class="row">
	    <div class="panel panel-default">
		    <div class="panel-heading">
		        <h1>
		            Search Images
		        </h1>	        
		    </div>
    <!-- will be used to show any messages 
    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif-->
			<div class="panel-body">
                <form action="/search" class="form-horizontal">
                    <div class="control-group">
                        <div class="controls form-inline">
                            <label for="keywords">Keys: </label>
                            <input type="text" class="input-small" placeholder="comma-separated-value, i.e. 'blue, flowers, desert'" name="keywords" id="keywords" size="60" value="<?php echo $app['request']->get('keywords'); ?>">
                            <label for="amount">Amount: </label>
                            <input type="number" class="input-small" placeholder="Number of images" id="amount" name="amount" value="<?php echo $app['request']->get('amount'); ?>">
                            <br/><br/>
                            <label for="width">Width: </label>
                            <input type="number" class="input-small" placeholder="Filter by width" id="width" name="width" value="<?php echo $app['request']->get('width'); ?>">
                            <label for="height">Height: </label>
                            <input type="number" class="input-small" placeholder="Filter by height" id="height" name="height" value="<?php echo $app['request']->get('height'); ?>">
                            <br/></br>
                            <label for="match_all">Match all keywords: </label>
                            <input type="checkbox" id="match_all" name="match_all" value="true" <?php echo $app['request']->get('match_all') ? "checked=checked" : ""; ?>>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <br/>
                        </div>
                    </div>
                </form>				
			    <table class="table table-striped">
			        <thead>
			            <tr>
			                <td>ID</td>
			                <td>Name</td>
			                <td>URL</td>
			                <td>Width</td>
			                <td>Height</td>
			            </tr>
			        </thead>
			        <tbody>
			        <?php
			        	if(isset($images)) 
			        	foreach($images as $image) {?>
			            <tr>
			                <td><?php echo $image->id; ?></td>
			                <td><?php echo $image->name; ?></td>
			                <td><?php echo $image->url; ?></td>
			                <td><?php echo $image->width; ?></td>
			                <td><?php echo $image->height; ?></td>
			            </tr>
			        <?php } ?>
			        </tbody>
			    </table>
			</div>
		</div>
	</div>
</div>
<?php $this->endBlock('main.content'); ?>