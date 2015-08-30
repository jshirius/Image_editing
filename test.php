<?php

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

?>

Now I can do the following:

<?php

if ($_FILES['originalfiles']) {
    $file_ary = reArrayFiles($_FILES['originalfiles']);

  //var_dump($file_ary);
    
    foreach ($file_ary as $file) {
        print 'File Name: ' . $file['name'];
        print 'File Type: ' . $file['type'];
        print 'File Size: ' . $file['size'];
        print 'TMP File: ' . $file['tmp_name'];
        
        print "<br>";
    }
}

?>