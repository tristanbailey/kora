<?php 

if(!isset($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT']=str_replace('\\','/',substr($_SERVER['SCRIPT_FILENAME'],0,0-strlen($_SERVER['PHP_SELF'])));
            if(!empty($_GET['src'])){
		$mysize = getimagesize("media/catalog/product/".$_GET['src']);
			
		$prodpicsize=$_GET['sz'];
			
		$width = $prodpicsize ? $prodpicsize : $prodpicsize;
		$height = $prodpicsize ? $prodpicsize : $prodpicsize;

		if($_GET['qual']){
			$quality = $_GET['qual'];
		} else {
			$quality = 75;
		}
            
            $sourceFilename = "media/catalog/product/".$_GET['src'];

            if(is_readable($sourceFilename)){
                include("phpThumb/phpthumb.class.php");
                $phpThumb = new phpThumb();
				
				$dirsplit = explode("/", $_GET['src']);
				$dirstruct = 'media/catalog/product/cache/';
				
				for($count=0; $count<(count($dirsplit)-1); $count++){
					$dirstruct = $dirstruct . $dirsplit[$count]."/";
				}

				$newdirstruct = substr_replace($dirstruct,"",-1);

				if (!file_exists($newdirstruct)){
					mkdir($newdirstruct, 0777, true);
				}

				
				
                $phpThumb->src = $sourceFilename;
		$phpThumb->zc = 1;
                $phpThumb->w = $width;
                $phpThumb->h = $height;
                $phpThumb->q = $quality;
                $phpThumb->config_imagemagick_path = '/usr/bin/convert';
                $phpThumb->config_prefer_imagemagick = false;
                $phpThumb->config_output_format = 'jpg';
                $phpThumb->config_error_die_on_error = true;
                //$phpThumb->config_document_root = '';
                //$phpThumb->config_temp_directory = APP . 'tmp';
                $phpThumb->config_cache_directory = $dirstruct;
                $phpThumb->config_cache_disable_warning = true;
				
                $justfilename = $dirsplit[(count($dirsplit)-1)];
		$justfilename = explode(".",$justfilename);
                //$cacheFilename = md5($_SERVER['REQUEST_URI']).'.jpg';
		$cacheFilename = "sq-".$justfilename[0]."-".$prodpicsize."x".$prodpicsize."-q".$quality.'.jpg';
                
                $phpThumb->cache_filename = $phpThumb->config_cache_directory.$cacheFilename;
                
                //Thanks to Kim Biesbjerg for his fix about cached thumbnails being regeneratd
                if(!is_file($phpThumb->cache_filename)){ // Check if image is already cached.
                    if ($phpThumb->GenerateThumbnail()) {
                        $phpThumb->RenderToFile($phpThumb->cache_filename);
                    } else {
                        die('Failed: '.$phpThumb->error);
                    }
                }
            
            if(is_file($phpThumb->cache_filename)){ // If thumb was already generated we want to use cached version
                $cachedImage = getimagesize($phpThumb->cache_filename);
                header('Content-Type: '.$cachedImage['mime']);
                readfile($phpThumb->cache_filename);
                exit;
            }
            
            
            } else { // Can't read source
                die("Couldn't read source image ".$sourceFilename);
            }
}
?> 