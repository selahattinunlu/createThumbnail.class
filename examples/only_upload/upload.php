<?php 

require('../../createThumbnail.php');

$ct = new createThumbnail();

$ct->start($_FILES['image']);

// Uzantı kontrolü
$ct->extensionControl();

// Upload Kontrolü
$ct->isUpload();

// Resme yeni isim
$ct->newName('new_image.jpg');

// Taşıma işlemi
$ct->moveUpload('upload/');

// Sonuç
$ct->result('Resim başarıyla yüklendi!');

?>
