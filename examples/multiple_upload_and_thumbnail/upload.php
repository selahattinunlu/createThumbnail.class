<?php 

require('../../createThumbnail.php');

// Sınıfımızı çağırırken Formdaki input'un name alanını belirtiyoruz
$ct = new createThumbnail();

$total_image = count($_FILES['image']['name']);

for($i = 0; $i < $total_image; $i++)
{
	$ct->start($_FILES['image'], $i);
	$ct->extensionControl();
	$ct->isUpload();
		$new_name = rand(0,999).time().'.jpg';
	$ct->newName($new_name);
	$ct->moveUpload('upload/');
	$ct->create_thumbnail('upload/thumb/', 'thumb_'.$new_name, 300, 300 );
}

$ct->result('SUCCESS MESSAGE');

?>