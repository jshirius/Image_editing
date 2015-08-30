<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"">
<link rel="stylesheet" type="text/css" href="body.css">

<title>画像の合成</title>

</head>
<body>

<!-- コンテナ開始 -->
<div id="container">


<!-- ヘッダ開始 -->
<div id="header">
写真合成
</div>
<!-- ヘッダ終了 -->


<!-- コンテンツ開始 -->
<div id="content">
写真などの名前をつけるときに使える


<form action="test.php" method="post" enctype="multipart/form-data">
<p>元の写真を選択してください</p>
  <input name="originalfiles[]" type="file" multiple="multiple" /><br />
  
<p>合成する写真</p>
  <input name="synfile" type="file"/><br />


  <input type="submit" value="Send files" />
</form>

</div>
<!-- コンテンツ終了 -->


<!-- フッタ開始 -->
<div id="footer">
［フッタ］
</div>
<!-- フッタ終了 -->


</div>
<!-- コンテナ終了 -->

</body>
</html>




