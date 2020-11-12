<?php
namespace Mvc0623\Controller;

class Thread extends \Mvc0623\Controller
{
	private $_imgType;
	private $_imgName;
	private $_lastInsertId;

	// getできたページの値を検証
	// filter_inputを使ってもいいかもしれない。
	private function _validateGetValue()
	{
		$sort = filter_input(INPUT_GET, 'sort');
		$category = filter_input(INPUT_GET, 'category');
		$search = filter_input(INPUT_GET, 'search');

		$this->page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
		if( is_null($this->page) )
		{
			$this->page = 1;
		}
		return [
			'sort'=>$sort, 
			'category'=>$category, 
			'search'=>$search, 
			'page'=>$this->page,
		];
	}



	/*******************************************					
	 * 何件取得するか引数に指定して話題のスレッドを取得
	*******************************************/					
	public function getHotTopics($quantity)
	{
		$threadModel = new \Mvc0623\Model\Thread();
		return $threadModel->selectHotTopics($quantity);
	}
	/*******************************************					
	 * 何件取得するか引数に指定して人気のスレッドを取得
	*******************************************/					
	public function getPopularThreads($quantity)
	{
		$threadModel = new \Mvc0623\Model\Thread();
		return $threadModel->selectPopularThreads($quantity);
	}


	/*********************************************
		各スレッドごとのコメント数を取得
	*********************************************/
	public function getCommentsFromThreadNo($thread_no)
	{
		$Thread = new \Mvc0623\Model\Thread();
		return $Thread->findCommentsFromThreadNo($thread_no);
	}
	/*********************************************
		スレッド番号からスレッドを取得
	*********************************************/
	public function getThreadFromNo()
	{
		$thread_no = (int)filter_input(INPUT_GET, 'thread');
		if( $thread_no )
		{
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->findThreadFromNo($thread_no);
		}
	}

	/*********************************************
		スレッド一覧取得
	*********************************************/
	public function getThreads()
	{
		try
		{
			$val = $this->_validateGetValue();
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->getThreads($val);
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
	}
	/*********************************************
		カテゴリ情報を取得
	*********************************************/
	public function getCategoryInfo()
	{
		try
		{
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->findCategoryInfo();
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
	}
	/*********************************************
		カテゴリIDから件数を取得
	*********************************************/
	public function getCountCategoryFromId($category_id)
	{
		try
		{
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->countCategoryFromId($category_id);
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
	}

	/************************
		返信処理
	************************/
	protected function reply()
	{
		$val = $this->_validateReply();
		if( $this->hasError() )
		{
			return;
		}

		try
		{
			$Reply = new \Mvc0623\Model\Reply();
			$Reply->reply($val);
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
		header('Location:'.SITE_URL.'?thread='.$val['thread_no'].'#jump');
		exit;
	}
	/************************
		返信内容を取得
	************************/
	public function getReplies($thread_no)
	{
		$Reply = new \Mvc0623\Model\Reply();
		return $Reply->findReplies($thread_no);
	}

	private function _validateReply()
	{
		$thread_no = filter_input(INPUT_POST, 'thread_no', FILTER_VALIDATE_INT);
		$auther = filter_input(INPUT_POST, 'reply_auther');
		$body = filter_input(INPUT_POST, 'reply_body');
		if( $auther === '' )
		{
			$auther = ANONYMOUS;
			$this->setValue('reply_auther', $auther);
		}
		if( $body === '' )
		{
			$this->setError('reply_body', '本文は必須です');
		}
		return [
			'thread_no'=>$thread_no,
			'auther'=>$auther,
		 	'body'=>$body];
	}


	/*********************************************
		新スレ投稿
	*********************************************/
	protected function createThread()
	{
		try
		{
			$val = $this->_validateThread();
			$this->_validateError();
			if( $this->hasError() )
			{
				$this->setValue('thread_title', $val['title']);
				$this->setValue('thread_body', $val['body']);
				$this->setValue('cat_id', $val['cat_id']);
				$this->setValue('thread_auther', $val['auther']);
				return;
			}
			$ext = $this->_validateType();
			$savePath = $this->_save($ext, $val);
			$this->_createThumbnail($savePath);

			$_SESSION['success'] = true;
		}
		catch(\Exception $e)
		{
			$this->setError('img', $e->getMessage() );
			$this->setValue('thread_title', $val['title']);
			$this->setValue('thread_body', $val['body']);
			$this->setValue('thread_auther', $val['auther']);
			$this->setValue('cat_id', $val['cat_id']);
			return;
		}
		header('Location:'.SITE_URL);
		exit;
	}

	private function _createThumbnailMain($savePath, $imgW, $imgH)
	{
		// サムネのサイズ計算
		// 黒画面
		// ソースからサイズ変換して黒画面に上書き
		// 保存

		$thumbH = round(( THUMB_W * $imgH ) / $imgW );
		$thumb = imagecreatetruecolor(THUMB_W, $thumbH);
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);

		switch($this->_imgType)
		{
		case IMAGETYPE_GIF:
			$srcImg = imagecreatefromgif($savePath);
			break;
		case IMAGETYPE_JPEG:
			$srcImg = imagecreatefromjpeg($savePath);
			break;
		case IMAGETYPE_PNG:
			$srcImg = imagecreatefrompng($savePath);
			break;
		}
		imagecopyresampled(
			$thumb, $srcImg, 0,0,0,0, THUMB_W, $thumbH, $imgW, $imgH);

		switch($this->_imgType)
		{
		case IMAGETYPE_GIF:
			imagegif($thumb, THUMBS_DIR.'/'.$this->_imgName);
			break;
		case IMAGETYPE_JPEG:
			imagejpeg($thumb, THUMBS_DIR.'/'.$this->_imgName);
			break;
		case IMAGETYPE_PNG:
			imagepng($thumb, THUMBS_DIR.'/'.$this->_imgName);
			break;
		}

	}
	private function _createThumbnail($savePath)
	{
		$imgSize = getimagesize($savePath);
		$imgW = $imgSize[0];
		$imgH = $imgSize[1];

		if( $imgW > THUMB_W )
		{
			$this->_createThumbnailMain($savePath, $imgW, $imgH);
			$Thread = new \Mvc0623\Model\Thread();
			$Thread->updateThumbnail($this->_lastInsertId);
		}
	}


	private function _validateType()
	{
		$this->_imgType = exif_imagetype($_FILES['thread_img']['tmp_name']);
		switch($this->_imgType)
		{
		case IMAGETYPE_GIF:
			return 'gif';
		case IMAGETYPE_JPEG:
			return 'jpeg';
		case IMAGETYPE_PNG:
			return 'png';
		default:
			throw new \Exception('gif/jpeg/pngファイルのみ対応');
		}
	}

	private function _validateThread()
	{
		$this->validateToken();

		$title = (string)filter_input(INPUT_POST, 'thread_title'); 
		$auther = (string)filter_input(INPUT_POST, 'thread_auther'); 
		$body = (string)filter_input(INPUT_POST, 'thread_body'); 
		$cat_id = (string)filter_input(INPUT_POST, 'cat_id'); 

		if( $title === '' )
		{
			$this->setError('thread_title', 'タイトルは必須です');
		}
		if( $auther === '' )
		{
			$this->setValue('thread_auther', ANONYMOUS);
			$auther = ANONYMOUS;
		}
		if( $body === '' )
		{
			$this->setError('thread_body', '本文は必須です');
		}
		if( $cat_id === '' )
		{
			$this->setError('cat_id', 'カテゴリは必須です');
		}
		return ['title'=>$title, 'auther'=>$auther, 
						'body'=>$body, 'cat_id'=>$cat_id];
	}

	private function _validateError()
	{
		if( !isset($_FILES['thread_img']) ||
		 		!isset($_FILES['thread_img']['error']) )
		{
			echo 'アップロードエラー';
			exit;
		}
		switch($_FILES['thread_img']['error'])
		{
			case UPLOAD_ERR_OK:
				return true;
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new \Exception('画像サイズが大きすぎます');
			case UPLOAD_ERR_NO_FILE:
				throw new \Exception('画像のアップロードは必須です');
			default:
				throw new \Exception('Err:'.$_FILES['thread_img']['error']);
		}
	}

	private function _save($ext, $val)
	{
		$this->_imgName = 
			sprintf('%s_%s.%s', time(), sha1(uniqid(mt_rand(),true)), $ext);
		$savePath = IMGS_DIR.'/'.$this->_imgName;

		$res =
		 	move_uploaded_file($_FILES['thread_img']['tmp_name'], $savePath);
		if( $res === false )
		{
			throw new \Exception('画像の保存に失敗しました。パーミッションを確認してください');
		}

		try
		{
			$Thread = new \Mvc0623\Model\Thread();
			$this->_lastInsertId = $Thread->createThread($val, $this->_imgName);
		}
		catch(\Exception $e)
		{
			echo $e->getMessage();
			exit;
		}
		return $savePath;
	}
}
?>
