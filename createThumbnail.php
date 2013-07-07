<?php 

/**
* @package createThumbnail Class
* @author Selahattin Ünlü 
* @link http://www.blogkeyf.com
*
*/

class createThumbnail{

	/**
	* $_FILES['xxxx'] değerinin ne olduğunu belirtir
	*/
	public $image;

	/**
	* Uploada izin verilen uzantılar dizisi
	*/
	public $extensions = array( 'jpeg', 'jpg', 'png', 'gif' );

	/**
	* Upload edilen resmin uzantısı
	*/
	public $extension;

	/**
	* Upload edilen resmin orjinal adı
	*/
	public $imageName;

	/**
	* Upload edilen resmin yeni adı
	*/
	public $imageNewName = '' ;

	/**
	* Upload edilen resmin yeni dizini
	*/
	public $imageDir = '' ;

	/**
	* Kontrol sonuçlarının aktarıldığı değişken
	*/
	public $control;

	/**
	* Uzantı hatası
	*/
	public $extensionError 	= 'Sadece jpg, png ve gif formatında resimler yükleyebilirsiniz.';

	/**
	* Upload hatası
	*/
	public $uploadError 	= 'Resim yüklenirken bir sorun oluştu.';

	/**
	* Taşıma hatası
	*/
	public $moveUploadError = 'Resim yüklenirken bir sorun oluştu.';

	/**
	* Hata Mesajlarını bir değişkende topluyoruz
	*
	*/
	public $error = '';

	/**
	* Php Hata Mesajları
	* Default olarak şuanda hatalar gizlenmektedir.
	* Eğer kritik hataların gösterilmesini istiyorsanız değişkenin değerini E_ALL olarak değiştirebilirsiniz.
	*/
	public $phpError = 0;


	/**
	* Başlangıç fonksiyonu
	* $_FILES['xdeger'] $image değişkenine aktarılır
	*/
	public function __construct( $image ){

		error_reporting( $this->phpError );

		$this->image = $image;

		// Resmin uzantısı
		$this->extension = @end( explode( '.', $this->image['name'] ) );

	}

	/**
	* Uzantı kontrolü:
	* Yüklenen dosya uzantısı $extensions dizisinde belirtilen uzantılardan farklıysa hata değeri döndürülür.
	*/
	public function extensionControl(){

		// Uzantı kontrolü
		if( in_array( $this->extension, $this->extensions ) ){
			$this->control = true;
		}else{
			$this->control = false;
			$this->error = $this->extensionError;
			echo $this->error;
		}

	}


	/**
	* Upload kontrolü
	* Bir önceki kontrolde hata yoksa işlem devam eder.
	* Resmin upload işleminin başarılı olup olmadığını kontrol eder.
	*/
	public function isUpload(){

		// Upload Kontrolü
		if( $this->control == true ){
			if( is_uploaded_file( $this->image['tmp_name'] ) ){
				$this->control = true;
			}else{
				$this->control = false;
				$this->error = $this->uploadError;
				echo $this->error;
			}
		}

	}

	/**
	* Resime yeni isim atıyoruz
	*
	* @param $name; Resmin yeni adını belirlediğimiz parametre
	* @example 'yeniresim.jpg'
	*/
	public function newName( $name ){

		$this->imageNewName = $name;

	}

	/**
	* Upload edilen resmi taşıma
	*
	* @param $save; Resmin taşınacağı dizin
	* @example 'upload/'
	*/
	public function moveUpload( $save ){

		if( $this->control == true ){

			// Belirtilen klasör bulunamazsa oluşturuyoruz
			if( !file_exists( $save ) ){
				mkdir( $save );
			}

			if( $this->imageNewName != '' ){
				$this->imageDir = $save;
				$move = $this->imageDir . $this->imageNewName;
				if( move_uploaded_file( $this->image['tmp_name'], $move  ) ){
					$this->control = true;
				}else{
					$this->control = false;
					$this->error = $this->moveUploadError;
					echo $this->error;
				}
			}

		}

	}


	/**
	* Thumbnail resim oluşturma
	* @author Alex (Phpacademy)
	* @link http://www.phpacademy.org
	* @param $save; Kaydedilmek istenen dizin
	* @param $name; Küçük resmin adı
	* @example 'kucuk_resim.jpg'
	* @param $width; Küçük resmin genişliği
	* @param $height; Küçük resmin yüksekliği
	*/
	function create_thumbnail( $save, $name, $width, $height ){

		if( $this->control == true ){
			// Kaydedilmek istenen dizin yoksa oluşturuyoruz
			if( !file_exists( $save ) ){
				mkdir( $save );
			}

			$save = $save . $name;

			// Kaynak resmin tam yolunu belirtiyoruz.
			$path = $this->imageDir . $this->imageNewName;

			$info = getimagesize( $path );
			$size = array( $info[0], $info[1] );

			if( $info['mime'] == 'image/jpeg' ){
				$src = imagecreatefromjpeg( $path );
			}else if( $info['mime'] == 'image/gif' ){
				$src = imagecreatefromgif( $path );
			}else if( $info['mime'] == 'image/png' ){
				$src = imagecreatefrompng( $path );
			}else{
				return false;
			}

			$thumb = imagecreatetruecolor( $width, $height );

			$src_aspect = $size[0] / $size[1];
			$thumb_aspect = $width / $height;

			if( $src_aspect < $thumb_aspect ){
				// narrover
				$scale = $width / $size[0];
				$new_size = array( $width, $width/$src_aspect );
				$src_pos = array( 0, ($size[1] * $scale - $height) / $scale / 2 );
			}else if( $src_aspect > $thumb_aspect ){
				// wider
				$scale = $height / $size[1];
				$new_size = array( $height * $src_aspect, $height );
				$src_pos  = array( ($size[0] * $scale - $width) / $scale / 2, 0 );
			}else{
				// some shape
				$new_size = array( $width, $height );
				$src_pos = array( 0, 0 );
			}

			$new_size[0] = max( $new_size[0], 1 );
			$new_size[1] = max( $new_size[1], 1 );

			imagecopyresampled( $thumb, $src, 0, 0, $src_pos[0], $src_pos[1], $new_size[0], $new_size[1], $size[0], $size[1] );

			if( $save === false ){
				return imagejpeg( $thumb );
			}else{
				return imagejpeg( $thumb, $save );
			}
		}

	}

	/**
	* Tüm işlem başarıyla tamamlandığında ekranda yazacak olan cümle
	* @param $sentence; Yazmasını istediğiniz cümle
	*/
	public function result( $sentence ){
		if( $this->control == true ){
			echo $sentence;
		}
	} 

}

?>