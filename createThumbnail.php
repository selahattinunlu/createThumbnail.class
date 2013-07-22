<?php 

/**
* @package createThumbnail Class
* @author Selahattin Ünlü 
* @link http://www.blogkeyf.com
*
* Translated by Vedat Kökmen
*/

class createThumbnail{

	/**
	* Tr - $_FILES['xxxx'] defines the value
	* En - 
	*/
	public $image;

	/**
	* Tr - Uploada izin verilen uzantılar dizisi  
	* En - Extension series which allowed to upload
	*/
	public $extensions = array( 'jpeg', 'jpg', 'png', 'gif' );

	/**
	* Tr - Upload edilen resmin uzantısı
	* En - The extension of uploaded pic
	*/
	public $extension;

	/**
	* Tr - Upload edilen resmin orjinal adı
	* En - The original name of uploaded pic
	*/
	public $imageName;

	/**
	* Tr - Upload edilen resmin yeni adı
	* En - New name of uploaded pic
	*/
	public $imageNewName = '' ;

	/**
	* Tr - Upload edilen resmin yeni dizini
	* En - New directory of uploaded pic 
	*/
	public $imageDir = '' ;

	/**
	* Tr - Kontrol sonuçlarının aktarıldığı değişken
	* En - Variable that control results transfered
	*/
	public $control;

	/** Directory error
	* Tr - Uzantı hatası
	* En - Extension Error
	*/
	public $extensionError 	= 'Sadece jpg, png ve gif formatında resimler yükleyebilirsiniz.';

	/** 
	* Tr - Upload hatası
	* En - Upload error 
	*/
	public $uploadError 	= 'Resim yüklenirken bir sorun oluştu.';

	/**
	* Tr - Taşıma hatası
	* En - Transport error 
	*/
	public $moveUploadError = 'Resim yüklenirken bir sorun oluştu.';

	/** We are collecting the error messages
	* Tr - Hata Mesajlarını bir değişkende topluyoruz
	* En - We are collecting the error messages in a variable
	*/
	public $error = '';

	/**
	* Tr - Php Hata Mesajları
	* 	   Default olarak şuanda hatalar gizlenmektedir.
	*      Eğer kritik hataların gösterilmesini istiyorsanız değişkenin değerini 
	*      E_ALL olarak değiştirebilirsiniz.
	* 
	* En - Php Error Messages
	*	   Errors hiding as default currently 
	*	   If  you like to see the critic errors you can change the value of variable as E_ALL
	*/
	public $phpError = 0;


	/**
	* Tr - Kurucu Fonksiyon
	*      $_FILES['deger'] $image değişkenine aktarılır
	* 
	* En - Creator Function
	*/
	public function __construct( $image ){

		error_reporting( $this->phpError );

		$this->image = $image;

		// Resmin uzantısı - Extension of pic
		$this->extension = @end( explode( '.', $this->image['name'] ) );

	}

	/**
	* Tr - Uzantı kontrolü:
	*      Yüklenen dosya uzantısı $extensions dizisinde belirtilen uzantılardan 
	*      farklıysa hata değeri döndürülür.
	* 
	* En -  Extension Control
	*       If uploaded file extension is different than defined extensions than $extension
 	*       directory  error value returns.	
	*/
	public function extensionControl(){

		// Uzantı kontrolü - Extension control
		if( in_array( $this->extension, $this->extensions ) ){
			$this->control = true;
		}else{
			$this->control = false;
			$this->error = $this->extensionError;
			echo $this->error;
		}

	}


	/**
	* Tr - Upload kontrolü
	* 	   Bir önceki kontrolde hata yoksa işlem devam eder.
	* 	   Resmin upload işleminin başarılı olup olmadığını kontrol eder.
	* 
	* En - Upload control
	*	   If there is no error in previous control the process continues.	
	*	   It controls the upload process if it's successful.
	*/	   
	public function isUpload(){

		// Upload Kontrolü - Upload Control
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
	* Tr - Resime yeni isim atıyoruz
	* En - Assinging the new name of pic
	* 
	* @param $name;
	* @example 'yeniresim.jpg'
	*/
	public function newName( $name ){

		$this->imageNewName = $name;

	}

	/**
	* Tr - Upload edilen resmi taşıma
	* En - Transporting uploaded pic
	* @param $save;
	* @example 'upload/'
	*/
	public function moveUpload( $save ){

		if( $this->control == true ){

			// Tr - Belirtilen klasör bulunamazsa oluşturuyoruz
			// En - If file extension won't find we create
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
	* Tr - Thumbnail resim oluşturma
	* @author Alex (Phpacademy)
	* @link http://www.phpacademy.org
	* @param $save; (Tr - Kaydedilmek istenen dizin) (En - The directory that wanted to save)
	* @param $name; (Tr - Küçük resmin adı) (En - The name of  thumbnail)
	* @example 'kucuk_resim.jpg'
	* @param $width;
	* @param $height;
	*/
	function create_thumbnail( $save, $name, $width, $height ){

		if( $this->control == true ){
			// Tr - Kaydedilmek istenen dizin yoksa oluşturuyoruz
			// En - We create the directory if there isn't. 
			if( !file_exists( $save ) ){
				mkdir( $save );
			}

			$save = $save . $name;

			// Tr - Kaynak resmin tam yolunu belirtiyoruz.
			// En - We defining exactly path of pic 
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
	* Tr - Tüm işlem başarıyla tamamlandığında ekranda yazacak olan cümle
	* En - The sentence that print on screen when all process is successful. 
	* @param $sentence;
	* @example 'Resim başarıyla yüklendi!';
	*/
	public function result( $sentence ){
		if( $this->control == true ){
			echo $sentence;
		}
	} 

}

?>