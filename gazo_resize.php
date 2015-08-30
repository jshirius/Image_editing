<html>
<head>
<title>画像合成・保存・リサイズ(現在使っていない)</title>
</head>
<body>



<?php
	require( "Image.php" );
    define("LOGO_RIGHT", -0);   //右端からの位置
    define("LOGO_BOTTOM", 0);   //下からの位置
    define("FIT_IMG","black_logo.png");	//埋め込む画像ファイル名
    define("MAKE_DIR","20111015");	//※作成するフォルダ名(写真のフォルダ名)
    define("SUM_MAKE_DIR","thumbnail");	//サムネイル
    define("RESIZE_SIZE",18);//比率
	

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


