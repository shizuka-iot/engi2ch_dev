<?php
namespace Mvc0623\Model;

class Thread extends \Mvc0623\Model
{
	/****************************************************
									カテゴリ情報を取得
	****************************************************/
	public function findCategoryInfo()
	{
		$sql = 'select * from category';
		$stmt = $this->pdo->query($sql);
		if( $stmt === false )
		{
			throw new \Exception('DBエラー');
		}
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}
	/*********************************************
		各スレッドごとのコメント数を取得
	*********************************************/
	public function findCommentsFromThreadNo($thread_no)
	{
		$sql = 'select count(thread_no) from reply where thread_no = ?';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
		return $stmt->fetchColumn();
	}

	/****************************************************
									指定スレッドNoのスレを取得
	****************************************************/
	public function findThreadFromNo($thread_no)
	{
		$sql = 'select * from thread join category on cat_id = category.id 
			where no = ? and delete_flag = 0';
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$thread_no]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		$result = $stmt->fetch();
		// var_dump($result);
		if (!$result)
		{
			// echo '記事が存在しません';
			return false;
		}
		return $result;
	}

	/****************************************************
						スレッドを取得(サブメソッドあり)		
	****************************************************/
	public function getThreads($val)
	{
		if( !empty($val['category']) )
		{
			return $this->getThreadsFromCategory($val['category'], $val['page']);
		}
		else if(isset($val['search']))
		{
			return $this->getThreadsFromSearch($val['search'], $val['page']);
		}
		else
		{
			return $this->getAllThreads($val['page']);
		}
	}

	/****************************************************
								全スレッドを取得
	****************************************************/
	public function getAllThreads($page)
	{
		$offset = THREADS_PER_PAGE * ($page - 1);
		$sql = sprintf(
			'select
			no, user_id, auther, title, body, cat_id, fileName,
			thumbnail_flag, thread.created_at, thread.updated_at, 
			category.id, cat_name
			from thread left outer join category on cat_id = category.id 
			where delete_flag = 0 
			order by no desc limit %d offset %d', THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->query($sql);

		if (!$stmt)
		{
			return false;
		}
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}


	/****************************************************
								スレッドをタイトル検索
	****************************************************/
	public function getThreadsFromSearch($search, $page)
	{
		$offset = THREADS_PER_PAGE * ($page - 1);
		$word = '%'.$search.'%';
		$sql = 'select
			no, user_id, auther, title, body, cat_id, fileName, thumbnail_flag, 
			thread.created_at, thread.updated_at, category.id, cat_name 
			from thread join category
			on cat_id = category.id where title like ? and delete_flag = 0 ';
		$sql.= sprintf(
				' order by no desc limit %d offset %d', THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$word]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}
	/****************************************************
								スレッドをカテゴリ別で取得
	****************************************************/
	public function getThreadsFromCategory($category, $page)
	{
		$offset = THREADS_PER_PAGE * ($page - 1);
		$sql = 'select
			no, user_id, auther, title, body, cat_id, fileName, thumbnail_flag, 
			thread.created_at, thread.updated_at, category.id, cat_name 
			from thread join category
			on cat_id = category.id where cat_name = ? and delete_flag = 0 ';
		$sql.= sprintf(
				' order by no desc limit %d offset %d', THREADS_PER_PAGE, $offset);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$category]);
		$stmt->setFetchMode(\PDO::FETCH_CLASS, 'stdClass');
		return $stmt->fetchAll();
	}

	/****************************************************
								新規スレッド作成
	****************************************************/
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
		if( $res === false )
		{
			throw new \Exception('書き込み失敗');
		}
		return $this->pdo->lastInsertId();
	}

	/****************************************************
								サムネ作成時にステートを更新
	****************************************************/
	public function updateThumbnail($lastInsertId)
	{
		$sql = 'update thread set thumbnail_flag = 1 where no = ?';
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute([$lastInsertId]);
		if( $res === false )
		{
			echo 'updateThumbnailerror';
			exit;
		}
	}

	/****************************************************
								スレッドの件数を取得
	****************************************************/
	public function countSelectedThreads($val)
	{
		$hold = '';
		$sql = 'select count(*) from thread ';
		$sql.= ' inner join category on cat_id = category.id ';
		if( !empty($val['category']) )
		{
			$sql .= ' where cat_name = ? and delete_flag = 0';
			$hold = $val['category'];
		}
		else if( isset($val['search']) )
		{
			$sql .= ' where title like ? and delete_flag = 0';
			$hold = '%'.$val['search'].'%';
		}
		else
		{
			$sql .= 'where delete_flag = 0';
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$hold]);
		return $stmt->fetchColumn();
	}

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
