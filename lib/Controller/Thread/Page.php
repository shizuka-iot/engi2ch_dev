<?php
namespace Mvc0623\Controller\Thread;

/*
 * ページコントローラーでやること。
 * １．プロパティの定義。private publicをよく考えて使い分ける。
 * ２．コンストラクタの定義
 * ３．検証
 * ４．ページリンク表示
 *
 */
class Page extends \Mvc0623\Controller\Thread
{

	/*
	 * プロパティの参照権限は非常に重要
	 * 久しぶりにページング処理を実装しようとしてこのファイルを見て思った。
	 * 結合度の高いクラスだと他のクラスを継承や参照するので、
	 * どのクラスのメソッド・プロパティかわかりにくい。
	 * その点プライベートなら即座にそのクラスでしか有効でないと分かる！！
	 */
	public $page; // ページ
	private $_totalThreads; // 全スレッド。DB接続して取得
	private $_totalPages; // トータルページ。全スレッドから計算
	private $_offset; // 除外する数
	private $_from; // 何ページから
	private $_to; // 何ページまで


	/*
	 * コンストラクタ
	 * ここで各プロパティに値をセット
	 * セットした値はページリンク表示メソッドで使う。
	 */
	public function __construct()
	{
		$val = $this->_validate(); // getできたページの値を検証

		// DB接続。例外処理。
		try {
			$Thread = new \Mvc0623\Model\Thread();
			$count = $Thread->countSelectedThreads($val);
		}
		catch (\Exception $e) {
			echo $e->getMessage();
		}

		$this->_totalThreads = (int)$count;
		$this->_totalPages = (int)ceil($this->_totalThreads / THREADS_PER_PAGE);
		$this->_offset = (int)(THREADS_PER_PAGE * ($this->page - 1));
		$this->_from = (int)($this->_offset + 1);
		$this->_to = 
			($this->_offset + THREADS_PER_PAGE) < $this->_totalThreads ?
			($this->_offset + THREADS_PER_PAGE) : $this->_totalThreads; 
	}


	/*
	 * getできたページの値を検証
	 */
	private function _validate()
	{
		$sort = filter_input(INPUT_GET, 'sort');
		$category = filter_input(INPUT_GET, 'category');
		$search = filter_input(INPUT_GET, 'search');
		$this->page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

		if (is_null($this->page)) {
			$this->page = 1;
		}
		return [
			'category'=>$category, 
			'search'=>$search, 
			'sort'=>$sort, 
			'page'=>$this->page
		];
	}


	/*
	 * ページリンクを表示
	 */
	public function showPaging()
	{
		// forで各ページのリンクを取得
		// 前後に前へリンク(現在ページ-1) 次へリンク(現在ページ+1)
		// 表示するのは1 …3 4 5 6 7 …最後という風にする。
		// current_page 
		$val = $this->_validate();

		if( !is_null($val['category']) && $val['category'] !== '' ) {
			$word = 'category='.$val['category'].'&';
		}
		elseif( !is_null($val['search']) && $val['search'] !== '' ) {
			$word = 'search='.$val['search'].'&';
		}
		else {
			$word = '';
		}

		// 並び替えの指定があれば$wordに追記
		if( !is_null($val['sort']) && $val['sort'] !== '' ) {
			$word .= 'sort='.$val['sort'].'&';
		}

		if ($this->_totalThreads === 0) {
			$html = '記事がありません';
		}
		else {
			$html = <<<EOD
			<p>全{$this->_totalThreads}件中、
				{$this->_from}〜{$this->_to}件を表示しています。
			</p>
			<div class="row page_numbers between">
EOD;

			/* prev */
			$html .= '<div class="prev_page_wrap">';

			if( $this->page > 1 ) {
				// ヒアドキュメント内で演算は無理なので外で演算する
				$prev_page = $this->page - 1;
				$html .= <<<EOL
				<a class="prev_page_link" href="?{$word}page={$prev_page}">
					<div class="center prev_page">前へ</div>
				</a>
EOL;
			}
			$html .= '</div>';

			/* ページリンクを格納する領域 */
			$html.= '<div class="row page_number_wrap">';

			/* １ページ */
			if ($this->page === 1) {
				$html .= '<span class="current_page">';
				$html .= '<div class="center first_page page_number">1</div>';
				$html .= '</span>';
			}
			else {
				$html .= '<a href="?'.$word.'page=1">';
				$html .= '<div class="center first_page page_number">1</div>';
				$html .= '</a>';
			}

			/* ページが飛んでたら...を表示 */
			if (($this->page - 2) > 2) {
				$html .= '<span class="space">';
				$html .= '<div class="center page_span page_number">...</div>';
				$html .= '</span>';
			}


			/* ループでページリンクを生成 */
			for($i=$this->page - 2; $i<=$this->page + 2; $i++) {
				if ($i > 1 && $i < $this->_totalPages) {
					if ($i === $this->page) {
						$html .= '<span class="current_page">';
						$html .= '<div class="center page_number">'.$i.'</div>';
						$html .= '</span>';
					}
					else {
						$html .= '<a href="?'.$word.'page='.$i.'">';
						$html .= '<div class="center page_number">'.$i.'</div>';
						$html .= '</a>';
					}
				}
			}

			/* ページが飛んでたら...を表示 */
			if ($this->_totalPages > ($this->page + 3)) {
				$html .= '<span class="space">';
				$html .= '<div class="center page_span page_number">...</div>';
				$html .= '</span>';
			}

			/* 最終ページ */
			if( $this->_totalPages > 1 ) {
				if( $this->page === $this->_totalPages ) {
					$html .= '<span class="current_page">';
					$html .= '<div class="center last_page page_number">'.$this->_totalPages.'</div>';
					$html .= '</span>';
				}
				else {
					$html .= '<a href="?'.$word.'page='.$this->_totalPages.'">';
					$html .= '<div class="center last_page page_number">'.$this->_totalPages.'</div>';
					$html .= '</a>';
				}
			}
			$html .= '</div>';

			/* 次へ */
			$html .= '<div class="next_page_wrap">';

			if( $this->page < $this->_totalPages ) {
				$html .= '<a class="next_page_link" href="?'.$word.'page='.($this->page+1).'">';
				$html .= '<div class="center next_page">次へ</div>';
				$html .= '</a>';
			}
			$html .= '</div>';
			$html .= '</div>';
		}
		echo $html;
	}
}
?>
