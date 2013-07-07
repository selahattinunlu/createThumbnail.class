<?php 

require('../../createThumbnail.php');

// Sınıfımızı çağırırken Formdaki input'un name alanını belirtiyoruz
$ct = new createThumbnail($_FILES['image']);

// Uzantı kontrolü
$ct->extensionControl();

// Upload Kontrolü
$ct->isUpload();

// Resme yeni isim
$ct->newName('new_image.jpg');

// Taşıma işlemi
$ct->moveUpload('upload/');

// Thumbnail oluşturma
$ct->create_thumbnail( 'upload/thumb/', 'thumb.jpg', 300, 300 );

// Thumbnail oluşturma
$ct->create_thumbnail( 'upload/thumb/', 'thumb2.jpg', 100, 100 );

// Sonuç
$ct->result('Resim başarıyla yüklendi!');

?>