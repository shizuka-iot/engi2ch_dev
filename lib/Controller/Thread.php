<?php
namespace Mvc0623\Controller;

class Thread extends \Mvc0623\Controller
{
	private $_imgType;
	private $_imgName;
	private $_lastInsertId;

	/*
	 * getできたページの値を検証
	 */
	private function _validateGetValue()
	{
		$sort = filter_input(INPUT_GET, 'sort');
		$category = filter_input(INPUT_GET, 'category');
		$search = filter_input(INPUT_GET, 'search');

		$this->page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
		if (is_null($this->page)) {
			$this->page = 1;
		}
		return [
			'sort'=>$sort, 
			'category'=>$category, 
			'search'=>$search, 
			'page'=>$this->page,
		];
	}


	/*
	 * 取得する件数を引数に指定して新着スレッドを取得
	 *
	 * @param int $quantity
	 */
	public function getNewThreads($quantity)
	{
		$threadModel = new \Mvc0623\Model\Thread();
		return $threadModel->selectNewThreads($quantity);
	}


	/*
	 * 取得する件数を引数に指定して話題のスレッドを取得
	 *
	 * @param int $quantity
	 */
	public function getHotTopics($quantity)
	{
		$threadModel = new \Mvc0623\Model\Thread();
		return $threadModel->selectHotTopics($quantity);
	}


	/*
	 * 何件取得するか引数に指定して人気のスレッドを取得
	 *
	 * @param int $quantity
	 */
	public function getPopularThreads($quantity)
	{
		$threadModel = new \Mvc0623\Model\Thread();
		return $threadModel->selectPopularThreads($quantity);
	}


	/*
	 * 各スレッドごとのコメント数を取得
	 *
	 * @param int $thread_no
	 */
	public function getCommentsFromThreadNo($thread_no)
	{
		$Thread = new \Mvc0623\Model\Thread();
		return $Thread->findCommentsFromThreadNo($thread_no);
	}


	/*
	 * スレッド番号からスレッドを取得
	 */
	public function getThreadFromNo()
	{
		$thread_no = (int)filter_input(INPUT_GET, 'thread');

		if ($thread_no) {
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->findThreadFromNo($thread_no);
		}
	}


	/*
	 * スレッド一覧取得
	 */
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


	/*
	 * カテゴリ情報を取得
	 */
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


	/*
	 * カテゴリIDから件数を取得
	 */
	public function getCountCategoryFromId($category_id)
	{
		try {
			$Thread = new \Mvc0623\Model\Thread();
			return $Thread->countCategoryFromId($category_id);
		}
		catch(\Exception $e) {
			echo $e->getMessage();
			exit;
		}
	}


	/*
	 * 返信処理
	 */
	protected function reply()
	{
		$val = $this->_validateReply();
		if ($this->hasError()) {
			return;
		}

		try {
			$Reply = new \Mvc0623\Model\Reply();
			$Reply->reply($val);
		}
		catch (\Exception $e) {
			echo $e->getMessage();
			exit;
		}
		header('Location:'.SITE_URL.'?thread='.$val['thread_no'].'#jump');
		exit;
	}


	/*
	 * 指定スレッドの返信内容を取得
	 *
	 * @param int $thread_no
	 */
	public function getReplies($thread_no)
	{
		$Reply = new \Mvc0623\Model\Reply();
		return $Reply->findReplies($thread_no);
	}

	/*
	 * 返信内容を検証
	 */
	private function _validateReply()
	{
		$thread_no = filter_input(INPUT_POST, 'thread_no', FILTER_VALIDATE_INT);
		$auther = filter_input(INPUT_POST, 'reply_auther');
		$body = filter_input(INPUT_POST, 'reply_body');
		if ($auther === '') {
			$auther = ANONYMOUS;
			$this->setValue('reply_auther', $auther);
		}
		if ($body === '') {
			$this->setError('reply_body', '本文は必須です');
		}
		return [
			'thread_no'=>$thread_no,
			'auther'=>$auther,
		 	'body'=>$body];
	}


	/*
	 * 新スレ投稿
	 */
	protected function createThread()
	{
		try {
			$val = $this->_validateThread();
			$this->_validateError();

			if ($this->hasError()) {
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
		catch(\Exception $e) {
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

	/*
	 * スレッドに投稿する画像のサムネイル作成メソッド
	 *
	 * @param string $savePath
	 * @param int $imgW
	 * @param int $imgH
	 */
	private function _createThumbnailMain($savePath, $imgW, $imgH)
	{
		// サムネのサイズ計算
		// 黒画面
		// ソースからサイズ変換して黒画面に上書き
		// 保存

		// $thumbH = round(( THUMB_W * $imgH ) / $imgW );
		// $thumb = imagecreatetruecolor(THUMB_W, $thumbH);
		$thumb = imagecreatetruecolor(THUMB_W, THUMB_W);
		imagealphablending($thumb, false);
		imagesavealpha($thumb, true);

		switch($this->_imgType) {
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

		if ($imgW > $imgH) {
			$src_x = round(($imgW - $imgH) / 2);
			imagecopyresampled(
				// $thumb, $srcImg, 0,0,0,0, THUMB_W, $thumbH, $imgW, $imgH);
				$thumb, $srcImg, 0,0, $src_x, 0, THUMB_W, THUMB_W, $imgH, $imgH);
		}
		else {
			$src_y = round(($imgH - $imgW) / 2);
			imagecopyresampled(
				$thumb, $srcImg, 0,0, 0, $src_y, THUMB_W, THUMB_W, $imgW, $imgW);
		}

		switch($this->_imgType) {
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

	/*
	 * サムネイル作成メソッド
	 *
	 * @param string $savePath
	 */
	private function _createThumbnail($savePath)
	{
		$imgSize = getimagesize($savePath);
		$imgW = $imgSize[0];
		$imgH = $imgSize[1];

		if ($imgW > THUMB_W) {
			$this->_createThumbnailMain($savePath, $imgW, $imgH);
			$Thread = new \Mvc0623\Model\Thread();
			$Thread->updateThumbnail($this->_lastInsertId);
		}
	}


	/*
	 * POSTされた一時ファイルの拡張子をチェック
	 */
	private function _validateType()
	{
		$this->_imgType = exif_imagetype($_FILES['thread_img']['tmp_name']);

		switch($this->_imgType) {
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


	/*
	 * POSTされた新規スレッド作成の各入力項目を検証
	 */
	private function _validateThread()
	{
		$this->validateToken();

		$title = (string)filter_input(INPUT_POST, 'thread_title'); 
		$auther = (string)filter_input(INPUT_POST, 'thread_auther'); 
		$body = (string)filter_input(INPUT_POST, 'thread_body'); 
		$cat_id = (string)filter_input(INPUT_POST, 'cat_id'); 

		if ($title === '') {
			$this->setError('thread_title', 'タイトルは必須です');
		}
		elseif (mb_strlen($title) > 140) {
			$this->setError('thread_title', 'タイトルは140文字以内で入力してください');
		}

		if ($auther === '') {
			$this->setValue('thread_auther', ANONYMOUS);
			$auther = ANONYMOUS;
		}
		elseif (mb_strlen($auther) > 50) {
			$this->setError('thread_auther', '名前は50文字以内で入力してください');
		}

		if ($body === '') {
			$this->setError('thread_body', '本文は必須です');
		}
		elseif (mb_strlen($body) > 1000) {
			$this->setError('thread_body', '本文は1000文字以内で入力してください');
		}

		if ($cat_id === '') {
			$this->setError('cat_id', 'カテゴリは必須です');
		}

		return ['title'=>$title, 'auther'=>$auther, 
						'body'=>$body, 'cat_id'=>$cat_id];
	}


	/*
	 * POSTされた新規スレッド作成の各入力項目を検証
	 */
	private function _validateError()
	{
		if( !isset($_FILES['thread_img']) ||
		 		!isset($_FILES['thread_img']['error']) ) {
			echo 'アップロードエラー';
			exit;
		}

		switch($_FILES['thread_img']['error']) {
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


	/*
	 * 新規スレッドの各項目をDBに書き込み、画像もフォルダに保存するメソッド
	 *
	 * @param string $ext // 画像の拡張子
	 * @param array $val // 新スレの各項目
	 */
	private function _save($ext, $val)
	{
		$this->_imgName = 
			sprintf('%s_%s.%s', time(), sha1(uniqid(mt_rand(),true)), $ext);
		$savePath = IMGS_DIR.'/'.$this->_imgName;

		$res =
		 	move_uploaded_file($_FILES['thread_img']['tmp_name'], $savePath);

		if ($res === false) {
			throw new \Exception('画像の保存に失敗しました。パーミッションを確認してください');
		}

		try {
			$Thread = new \Mvc0623\Model\Thread();
			$this->_lastInsertId = $Thread->createThread($val, $this->_imgName);
		}
		catch (\Exception $e) {
			echo $e->getMessage();
			exit;
		}
		return $savePath;
	}
}
?>
