<!DOCTYPE html>
<html>
    <head>
        <title>Error page.</title>
    </head>
    <body>
        <h3>Error page</h3>
        <p><?php echo isset($message) ? $message : "Whoops, looks like something went wrong."; ?></p>
    </body>
</html>