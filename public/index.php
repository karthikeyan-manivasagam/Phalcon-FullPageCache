<?php

error_reporting(E_ALL);

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
	 
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);

//// File caching logic starts here... 
	 
// Cache the file for 2 days
 $frontendOptions = [
     'lifetime' => 172800
 ];


         $frontCache = new \Phalcon\Cache\Frontend\Output($frontendOptions);


        $folder = explode('/',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $folder1 = $folder[1];
        $count = count($folder);
        $file = end($folder);
        reset($folder);
        $key = $count - 1;
        unset($folder[$key]);
        $path = implode('/',$folder);
        if(!is_dir('cache/'.$path)) $value = mkdir('cache/'.$path, 0777, true);

 // Create an output cache

 // Set the cache directory
 $backendOptions = [
     'cacheDir' => 'cache/'.$path.'/' ,
 ];

 // Create the File backend
 $cache = new \Phalcon\Cache\Backend\File($frontCache, $backendOptions);

        if($folder1 == "" || $file == "")  $file = "index"; 
        $content = $cache->start($file.".html");  
 
 
 if ($content === null) {
     echo '<h1>', time(), '</h1>';
	echo $application->handle()->getContent(); 
	 
     $cache->save();
 } else {
 
     echo $content;
	 
 }
	
//// File caching logic ends here... 
   
	
	
	
	
} catch (Phalcon\Exception $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
}
