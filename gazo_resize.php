<html>
<head>
<title>画像合成・保存・リサイズ</title>
</head>
<body>



<?php
    define("LOGO_RIGHT", -0);   //右端からの位置
    define("LOGO_BOTTOM", 0);   //下からの位置
    define("FIT_IMG","black_logo.png");	//埋め込む画像ファイル名
    define("MAKE_DIR","20111015");	//※作成するフォルダ名(写真のフォルダ名)
    define("SUM_MAKE_DIR","thumbnail");	//サムネイル
    define("RESIZE_SIZE",18);//比率
	
class Image {
    
    var $file;
    var $image_width;
    var $image_height;
    var $width;
    var $height;
    var $ext;
    var $types = array('','gif','jpeg','png','swf');
    var $quality = 80;
    var $top = 0;
    var $left = 0;
    var $crop = false;
    var $type;
    
    function Image($name='') {
        $this->file = $name;
        $info = getimagesize($name);
        $this->image_width = $info[0];
        $this->image_height = $info[1];
        $this->type = $this->types[$info[2]];
        $info = pathinfo($name);
        $this->dir = $info['dirname'];
        $this->name = str_replace('.'.$info['extension'], '', $info['basename']);
        $this->ext = $info['extension'];
    }
    
    function dir($dir='') {
        if(!$dir) return $this->dir;
        $this->dir = $dir;
    }
    
    function name($name='') {
        if(!$name) return $this->name;
        $this->name = $name;
    }
    
    function width($width='') {
        $this->width = $width;
    }
    
    function height($height='') {
        $this->height = $height;
    }
    
    function resize($percentage=50) {
        if($this->crop) {
            $this->crop = false;
            $this->width = round($this->width*($percentage/100));
            $this->height = round($this->height*($percentage/100));
            $this->image_width = round($this->width/($percentage/100));
            $this->image_height = round($this->height/($percentage/100));
        } else {
            $this->width = round($this->image_width*($percentage/100));
            $this->height = round($this->image_height*($percentage/100));
        }
        
    }
    
    function crop($top=0, $left=0) {
        $this->crop = true;
        $this->top = $top;
        $this->left = $left;
    }
    
    function quality($quality=80) {
        $this->quality = $quality;
    }
    
    function show() {
        $this->save(true);
    }
    
    function save($show=false) {

        if($show) @header('Content-Type: image/'.$this->type);
        
        if(!$this->width && !$this->height) {
            $this->width = $this->image_width;
            $this->height = $this->image_height;
        } elseif (is_numeric($this->width) && empty($this->height)) {
            $this->height = round($this->width/($this->image_width/$this->image_height));
        } elseif (is_numeric($this->height) && empty($this->width)) {
            $this->width = round($this->height/($this->image_height/$this->image_width));
        } else {
            if($this->width<=$this->height) {
                $height = round($this->width/($this->image_width/$this->image_height));
                if($height!=$this->height) {
                    $percentage = ($this->image_height*100)/$height;
                    $this->image_height = round($this->height*($percentage/100));
                }
            } else {
                $width = round($this->height/($this->image_height/$this->image_width));
                if($width!=$this->width) {
                    $percentage = ($this->image_width*100)/$width;
                    $this->image_width = round($this->width*($percentage/100));
                }
            }
        }
        
        if($this->crop) {
            $this->image_width = $this->width;
            $this->image_height = $this->height;
        }

        if($this->type=='jpeg') $image = imagecreatefromjpeg($this->file);
        if($this->type=='png') $image = imagecreatefrompng($this->file);
        if($this->type=='gif') $image = imagecreatefromgif($this->file);
        
        $new_image = imagecreatetruecolor($this->width, $this->height);
        imagecopyresampled($new_image, $image, 0, 0, $this->top, $this->left, $this->width, $this->height, $this->image_width, $this->image_height);
        
        $name = $show ? null: $this->dir.DIRECTORY_SEPARATOR.$this->name.'.'.$this->ext;
        if($this->type=='jpeg') imagejpeg($new_image, $name, $this->quality);
        if($this->type=='png') imagepng($new_image, $name);
        if($this->type=='gif') imagegif($new_image, $name);
        
        imagedestroy($image); 
        imagedestroy($new_image);
        
    }
    
}

    
    
    //現在のファイルパスを取得する
    $rpath = realpath($path);
    echo $rpath = $rpath . "/img/". MAKE_DIR . "/";
    echo "<br>";
   
	//写真が入っているディレクトリ開く
	if(($fldr = opendir($rpath)) == false){
		//ディレクトリのオープン失敗
		//エラーを返す
		echo "そんなディレクトリない";
		print_r(error_get_last());

		return ;
	}
    
	//ファイル名をリスト表示する
	while($obj = readdir($fldr)){
		if (is_dir($obj) == FALSE){
			//写真(*.jpgのパターンにマッチするもの)を表示
			if(mb_ereg("(.jpg|.JPG)$", $obj) == true){
				
				//ディレクトリ作成か
				if(!is_dir($rpath . MAKE_DIR . "/")){
					//ディレクトリ作成
					umask(0);
					mkdir($rpath . MAKE_DIR . "/",0777);
				}
				
				//サムネイルディレクトリ作成か
				if(!is_dir($rpath . MAKE_DIR . "/" . SUM_MAKE_DIR ."/")){
					//ディレクトリ作成
					umask(0);
					mkdir($rpath . MAKE_DIR . "/" . SUM_MAKE_DIR ."/");
				}
					
			    $file1 = $rpath. "/". $obj;                    //　ベース画像ファイル
			    $file2 = "./img/fit/". FIT_IMG;                    		//　埋め込み画像ファイル7
			    $file3 = $rpath. "". MAKE_DIR. "/". $obj;                            //　画像保存先
			    $file4 = $rpath. "". MAKE_DIR. "/".  SUM_MAKE_DIR ."/".$obj;                            //　画像保存先(サムネイル)
			    $img = ImageCreateFromJPEG($file1);                    //　ベース画像ファイル読み込み
			    $img2 = ImageCreateFromPNG($file2);                    //　埋め込み画像ファイル読み込み
			    $size1 = GetImageSize($file1);                        //　ベース画像のサイズを取得
			    
			    //print_r($size1);
			    $size2 = GetImageSize($file2);                        //　埋め込み画像のサイズを取得
			    $left = ($size1[0] - $size2[0]) - LOGO_RIGHT;                    //　埋め込み位置（右)
			    $top = ($size1[1] - $size2[1]) - LOGO_BOTTOM;                    //　埋め込み位置（下）

			    ImageCopy($img, $img2, $left, $top, 0, 0, $size2[0], $size2[1]);     //　画像合成
			    ImageJPEG($img, $file3,100);                        //　画像保存
			    ImageJPEG($img, $file4,100);//サムネイル保存
			    
			    ImageDestroy($img);
			    ImageDestroy($img2);
			    
			    //サムネイルフォルダに保存した写真をリサイズ
				$thumb = new Image($file4); 
				$thumb->resize(RESIZE_SIZE); 
				$thumb->save();
					
			}
		}
	}

?>

画像を合成・保存しました。<br>
<br>
↓PHPにて合成した画像。<br>
<img src="<?php echo $file3; ?>"><br>
<br>
元の画像。<br>
<img src="<?php echo $file1; ?>" style="float:left">
<img src="<?php echo $file2; ?>"><br>
</body>
</html>


