<?php
namespace Mvc0623\Model;

class Thread extends \Mvc0623\Model
{
	public function vote()
	{
		switch($_POST['mode']) {
			// goodボタンが押された場合の処理
			case 'good':
				
				// トランザクション開始
				$this->pdo->beginTransaction();

					// クッキーにgoodボタンを押した痕跡があればキャンセル処理
					if( isset($_COOKIE['good_thread_'.$_POST['thread_no']]) &&
										$_COOKIE['good_thread_'.$_POST['thread_no']] === '1')	{
						// goodを下げる（キャンセル）
						$this->_down_good_thread_count($_POST['thread_no']);
						// クッキーも削除してgoodをなかったことに。
						setcookie('good_thread_'.$_POST['thread_no'], '', time()-60*60*24);
					}
					else {
						// goodカウントを上げる。
						$this->_up_good_thread_count($_POST['thread_no']);

						// goodが押されたことをクッキーに記憶させる。
						setcookie('good_thread_'.$_POST['thread_no'], true, time()+60*60*24);
					}
					// 既にbadも押されていたらbadカウントを下げる。
					if( isset($_COOKIE['bad_thread_'.$_POST['thread_no']]) &&
										$_COOKIE['bad_thread_'.$_POST['thread_no']] === '1') {
						// badを下げる（キャンセル）
						$this->_down_bad_thread_count($_POST['thread_no']);

						// クッキーも削除してbadをなかったことに。
						setcookie('bad_thread_'.$_POST['thread_no'], '', time()-60*60*24);
					}

				// トランザクション終了
				$this->pdo->commit();


				// good/bad両方の値を取り出し。
				$vote['good'] = $this->_get_good_thread_count($_POST['thread_no']);
				$vote['bad'] = $this->_get_bad_thread_count($_POST['thread_no']);
				return $vote;

			// badボタンが押された場合の処理
			case 'bad':

				// トランザクション開始
				$this->pdo->beginTransaction();

					// クッキーにbadボタンを押した痕跡があればキャンセル処理
					if (isset($_COOKIE['bad_thread_'.$_POST['thread_no']]) &&
										$_COOKIE['bad_thread_'.$_POST['thread_no']] === '1') {
						// badカウントをもとに戻す
						$this->_down_bad_thread_count($_POST['thread_no']);
						// クッキーも削除してbadボタンが押された痕跡を消す
						setcookie('bad_thread_'.$_POST['thread_no'], '', time()-60*60*24);
					}
					// クッキーにbadボタンを押された痕跡がないときはbadカウントを増やす
					else {
						// badカウントを上げる。
						$this->_up_bad_thread_count($_POST['thread_no']);

						// badが押されたことをクッキーに記憶させる。
						setcookie('bad_thread_'.$_POST['thread_no'], true, time()+60*60*24);
					}
					// 既にgoodも押されていたらgoodカウントを下げる。
					if (isset($_COOKIE['good_thread_'.$_POST['thread_no']]) &&
										$_COOKIE['good_thread_'.$_POST['thread_no']] === '1') {
						$this->_down_good_thread_count($_POST['thread_no']);
						// goodボタンが押されたのをなかったことに。
						setcookie('good_thread_'.$_POST['thread_no'], '', time()-60*60*24);
					}

				// トランザクション終了
				$this->pdo->commit();

				// good/bad両方の値を取り出し。
				$vote['good'] = $this->_get_good_thread_count($_POST['thread_no']);
				$vote['bad'] = $this->_get_bad_thread_count($_POST['thread_no']);
				return $vote;
		}
	}


	/*
	 * 指定スレッドのgoodカウントをアップ
	 *
	 * @param int $thread_no
	 */
	private function _up_good_thread_count($thread_no)
	{
		// 更新
		$sql = 'update thread set good = good + 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
	}


	/*
	 * 指定スレッドのgoodカウントをアップ
	 *
	 * @param int $thread_no
	 */
	private function _get_good_thread_count($thread_no)
	{
		// 更新した値を取り出し
		$sql = sprintf('select good from thread where no = %s', $thread_no);
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchColumn();
	}


	/*
	 * 指定スレッドのbadカウントをアップ
	 *
	 * @param int $thread_no
	 */
	private function _up_bad_thread_count($thread_no)
	{
		// 更新
		$sql = 'update thread set bad = bad + 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);

	}


	/*
	 * 指定スレッドのbadカウントを取得
	 *
	 * @param int $thread_no
	 */
	private function _get_bad_thread_count($thread_no)
	{
		// 更新した値を取り出し
		$sql = sprintf('select bad from thread where no = %s', $thread_no);
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchColumn();
	}


	/*
	 * 指定スレッドのgoodカウントをダウン
	 *
	 * @param int $thread_no
	 */
	private function _down_good_thread_count($thread_no)
	{
		// 更新
		$sql = 'update thread set good = good - 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
	}


	/*
	 * 指定スレッドのbadカウントをダウン
	 *
	 * @param int $thread_no
	 */
	private function _down_bad_thread_count($thread_no)
	{
		// 更新
		$sql = 'update thread set bad = bad - 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
	}


	/*
	 * カテゴリ情報を取得
	 */
	public function findCategoryInfo()
	{
		$sql = 'select * from category order by id desc';
		$stmt = $this->pdo->query($sql);

		if( $stmt === false ) {
			throw new \Exception('DBエラー');
		}

		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * 指定スレッドのコメント数を取得
	 *
	 * @param int $thread_no
	 */
	public function findCommentsFromThreadNo($thread_no)
	{
		$sql = 'select count(thread_no) from reply where thread_no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
		return $stmt->fetchColumn();
	}


	/*
	 * 指定スレッドNoのスレを取得
	 *
	 * @param int $thread_no
	 */
	public function findThreadFromNo($thread_no)
	{
		$sql = 'select 
			thread.no, thread.auther, thread.title, thread.body, cat_id, thread.fileName,
			thread.thumbnail_flag, thread.created_at, thread.updated_at, 
			category.id, category.cat_name, thread.good, thread.bad, thread.thumbnail_flag
		 	from thread join category on cat_id = category.id 
			where no = ? and delete_flag = 0';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		$result = $stmt->fetch();

		if (!$result) {
			return false;
		}
		return $result;
	}

	/*
	 * スレッドを取得(サブメソッドあり)		
	 */
	public function getThreads($val)
	{
		if( !empty($val['category']) ) {
			return $this->getThreadsFromCategory($val['category'], $val['page'], $val['sort']);
		}
		else if(isset($val['search'])) {
			return $this->getThreadsFromSearch($val['search'], $val['page'], $val['sort']);
		}
		else {
			return $this->getAllThreads($val['page'], $val['sort']);
		}
	}


	/*
	 * 全スレッドを取得
	 */
	public function getAllThreads($page, $sort)
	{
		$order = $this->_generateSqlOrderByPostedValue($sort);
		$offset = THREADS_PER_PAGE * ($page - 1);
		$sql = '
			select
				thread.no, 
				thread.auther, 
				thread.title, 
				thread.body, 
				cat_id, 
				thread.fileName,
				thread.thumbnail_flag, 
				thread.created_at, 
				thread.updated_at, 
				category.id, 
				category.cat_name
			from thread 
			left outer join category 
				on cat_id = category.id 
			left outer join count_comment 
				on thread.no = thread_no 
			where thread.delete_flag = 0 ';
		$sql .= sprintf('
			%s limit %d offset %d', $order, THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->query($sql);

		if (!$stmt) {
			return false;
		}

		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * フォームから送られた値からSQLの並び替え(order by)を生成するメソッド
	 *
	 * @param string $sort
	 */
	public function _generateSqlOrderByPostedValue($sort)
	{
		switch ($sort) {
		case 'new':
			return 'order by no desc';
		case 'old':
			return 'order by no asc';
		case 'popular':
			return 'order by round( good / (good + bad) * 100 ) desc, good desc';
		case 'comment':
			return 'order by comments desc';
		default:
			return 'order by round( good / (good + bad) * 100 ) desc, good desc';
		}
	}


	/*
	 * 指定件数の話題のスレッドを取得
	 *
	 * @param int $quantity
	 */
	public function selectHotTopics($quantity)
	{
		$sql = '
			select
				no, 
				auther, 
				title, 
				body, 
				cat_id, 
				fileName, 
				thumbnail_flag, 
				thread.created_at, 
				thread.updated_at, 
				category.id, 
				cat_name 
			from thread 
				left outer join category
					on cat_id = category.id 
				left outer join count_comment 
					on thread.no = thread_no 
			where thread.delete_flag = 0 and not (category.id = 1) 
			order by (good + bad) desc,
			comments desc
			';
		$sql .= sprintf(' limit %d ', $quantity);
		$stmt = $this->pdo->query($sql);

		if (!$stmt) {
			return false;
		}

		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * 指定件数の新着スレッドを取得
	 *
	 * @param int $quantity
	 */
	public function selectNewThreads($quantity)
	{
		$sql = '
			select
				no, 
				auther, 
				title, 
				body, 
				cat_id, 
				fileName, 
				thumbnail_flag, 
				thread.created_at, 
				thread.updated_at, 
				category.id, 
				cat_name 
			from thread 
				left outer join category
					on cat_id = category.id 
			where thread.delete_flag = 0 
			order by thread.no desc
			';
		$sql .= sprintf(' limit %d ', $quantity);
		$stmt = $this->pdo->query($sql);

		if (!$stmt) {
			return false;
		}

		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * 人気のスレッドを取得
	 *
	 * @param int quantity
	 */
	public function selectPopularThreads($quantity)
	{
		$sql = '
			select
				no, 
				auther, 
				title, 
				body, 
				cat_id, 
				fileName, 
				thumbnail_flag, 
				thread.created_at, 
				thread.updated_at, 
				category.id, 
				cat_name 
			from thread 
				left outer join category
					on cat_id = category.id 
				left outer join count_comment 
					on thread.no = thread_no 
			where thread.delete_flag = 0 
			order by round ( good / (good + bad) * 100 ) desc,
			good desc
			';
		$sql .= sprintf(' limit %d ', $quantity);
		$stmt = $this->pdo->query($sql);

		if (!$stmt) {
			return false;
		}

		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * スレッドをタイトル検索
	 *
	 * @param string $search
	 * @param int $page
	 * @param string $sort
	 */
	public function getThreadsFromSearch($search, $page, $sort)
	{
		$order = $this->_generateSqlOrderByPostedValue($sort);
		$offset = THREADS_PER_PAGE * ($page - 1);
		$word = '%'.$search.'%';
		$sql = 'select
			no, auther, title, body, cat_id, fileName, thumbnail_flag, 
			thread.created_at, thread.updated_at, category.id, cat_name 
			from thread left outer join category
			on cat_id = category.id 
			left outer join count_comment on thread.no = thread_no 
			where title like ? and delete_flag = 0 ';
		$sql .= sprintf('
			%s limit %d offset %d', $order, THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$word]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * スレッドをカテゴリ別で取得
	 *
	 * @param string $category
	 * @param int $page
	 * @param string $sort
	 */
	public function getThreadsFromCategory($category, $page, $sort)
	{
		$order = $this->_generateSqlOrderByPostedValue($sort);
		$offset = THREADS_PER_PAGE * ($page - 1);
		$sql = 'select
			no, auther, title, body, cat_id, fileName, thumbnail_flag, 
			thread.created_at, thread.updated_at, category.id, cat_name 
			from thread join category
			on cat_id = category.id 
			left outer join count_comment on thread.no = thread_no 
			where cat_name = ? and delete_flag = 0 ';
		$sql .= sprintf('
			%s limit %d offset %d', $order, THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$category]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/*
	 * 新規スレッド作成
	 *
	 * @param array $val
	 * @param string $fileName
	 */
	public function createThread($val, $fileName)
	{
		$sql = 'insert into thread
			(auther, title, body, cat_id, fileName, created_at, updated_at) values
		 (:auther, :title, :body, :cat_id, :fileName, now(), now() )';
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute([
			':auther'=>$val['auther'],
			':title'=>$val['title'],
			':body'=>$val['body'],
			':cat_id'=>$val['cat_id'],
		 	':fileName'=>$fileName
		]);
		// 上で作成したレコードのid(スレッドNo)を取得
		$lastInsertId = $this->pdo->lastInsertId();

		if( $res === false ) {
			throw new \Exception('書き込み失敗');
		}
		return $lastInsertId;
	}


	/*
	 * サムネ作成時にフラグを更新
	 */
	public function updateThumbnail($lastInsertId)
	{
		$sql = 'update thread set thumbnail_flag = 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute([$lastInsertId]);

		if( $res === false ) {
			echo 'updateThumbnailerror';
			exit;
		}
	}


	/*
	 * スレッドの件数を取得
	 *
	 * @param array $val
	 */
	public function countSelectedThreads($val)
	{
		$hold = '';
		$sql = 'select count(*) from thread ';
		$sql.= ' inner join category on cat_id = category.id ';

		if( !empty($val['category']) ) {
			$sql .= ' where cat_name = ? and delete_flag = 0';
			$hold = $val['category'];
		}
		elseif( isset($val['search']) ) {
			$sql .= ' where title like ? and delete_flag = 0';
			$hold = '%'.$val['search'].'%';
		}
		else {
			$sql .= 'where delete_flag = 0';
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$hold]);
		return $stmt->fetchColumn();
	}


	/*
	 * カテゴリIDからカテゴリ別の記事数を取得
	 * ナビゲーションのカテゴリ一覧の各カテゴリの()に
	 * 表示するカテゴリ数
	 */
	public function countCategoryFromId($category_id)
	{
		$sql = 'select count(no) from thread join category on cat_id = id';
		$sql.= ' where cat_id = ? and delete_flag = 0';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$category_id]);
		return $stmt->fetchColumn();
	}
}
?>
