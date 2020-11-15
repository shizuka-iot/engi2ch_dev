'use strict';

// グローバル変数
let px = 0; // e.pageX
let py = 0; // e.pageY
let ox = 0; // e.offsetX
let oy = 0; // e.offsetY


/* 円を描くメソッド */
class Circle
{
	constructor(
		canvas_id, // キャンバスIDを指定
		sectorInfo, // 表示するデータを配列で受け取る
		radius, // 円グラスの半径を指定
		center_x, // 中心X座標
		center_y, // 中心Y座標
		type, // 表示するグラスのタイプを指定
		max // レーダーチャート用。基準となる最大値。円グラフには影響しない
	)
	{
		// コンストラクタで受け取った各扇の情報をプロパティに格納
		this.sectorInfo = sectorInfo;

		/*
		// 配列を量の降順にソート
		if(type !== 2)
		{
			this.sectorInfo.sort(function(a,b) {
				return (b[2] - a[2]);
			});
		}
		*/


		/****************************************
		 * コンストラクタの引数をプロパティに格納
		****************************************/
		this.radius = radius;
		this.centerX = center_x;
		this.centerY = center_y;
		this.type = type;
		this.max = max;


		/*****************************************
		 * キャンバスの初期化
		*****************************************/
		this.canvas = canvas_id;
		this.can = document.getElementById(canvas_id); 
		// this.can = document.querySelector('#can');でもオッケー
		

		// ブラウザがキャンバスに対応しているかチェック
		if( typeof this.can.getContext === 'undefined')
		{
			return;
		}

		// 取得したキャンバスのコンテキストを取得。
		this.con = this.can.getContext('2d');


		/* イベントリスナー */
		window.addEventListener('mousemove', this.mouseMove, false);
		// window.addEventListener('mousemove', mouseMove, false);


		this.edge_flag = 1;
		/* 角度に関するプロパティ */
		this.sum_of_sector_value = 0; //　各項目の量の合計を保持するプロパティ
		this.angles = []; // 各項目の持つ角度を配列で保持。
		this.startAngles = []; // 各扇の開始角
		this.finishAngles = []; // 各扇の終了角
		this.halfDegrees = []; // 円グラフの中に表示されるパーセンテージを表示するのは各扇の半分の角度
		/* 扇の中心座標をいれる配列 */
		this.eachSectorCenters = [];


		this.angle = 0;// マウスと中心座標が形成する角度
		// this.degree = 0;// 修正前の角度

		this.hit_flag = [];// 扇にマウスオーバーで半径を増やすかどうか判定するフラグ。
		this.increase = [];// マウスオーバーで増える扇の半径の増加量。
		this.hitted = [];// マウスオーバーか否か。


		// グラフの中に表示するパーセンテージの座標
		this.half_degree_x = 0;
		this.half_degree_y = 0;

		/* 座標に使用するプロパティ */
		this.sqrt_xy = 0;// 中心座標とマウスの平方根
		this.rect = this.can.getBoundingClientRect();
		this.absoluteCenterX = this.cutNum(this.rect.left+this.centerX+window.pageXOffset);
		this.absoluteCenterY = this.cutNum(this.rect.top+this.centerY+window.pageYOffset);

		/********************************************
		 * コンストラクタメソッド
		********************************************/
		this.init_array();// 受け取った配列を0で初期化。
		this.setQuantities();// this.sum_of_sector_valueに量の合計を格納している。
		this.setEachAngles();// 各扇が持つ角度を配列にセット。
		this.setStartFinishAngles();// this.startAngles・this.finishAnglesに開始角・終了角を格納。
		this.setHalfDegrees();
		this.checkSectorValueForEdge()

	}// コンストラクタ

	restart()
	{
		this.setQuantities();// this.sum_of_sector_valueに量の合計を格納している。
		this.setEachAngles();// 各扇が持つ角度を配列にセット。
		this.setStartFinishAngles();// this.startAngles・this.finishAnglesに開始角・終了角を格納。
		this.setHalfDegrees();
		this.checkSectorValueForEdge()
	}




	/****************************************************
	 * イベントリスナー
	****************************************************/
	// マウスイベント。イベント情報はグローバル変数に代入。
	mouseMove(e)
	{
		// クライアントはブラウザの左上を0,0としてそこからのマウスの座標を取得。
		// オフセットはターゲットエレメントの左上を0,0としてそこからのマウスの座標を取得。
		px = e.pageX;
		py = e.pageY;
		ox = e.offsetX;
		oy = e.offsetY;
	}
	
	/****************************************************
	 * 更新・描画
	****************************************************/
	/* 更新メソッド。この中に色んな処理の更新をまとめて入れる */
	update()
	{
		/* 中心座標の更新 */
		this.rect = this.can.getBoundingClientRect();
		this.absoluteCenterX = this.cutNum(this.rect.left+this.centerX+window.pageXOffset);
		this.absoluteCenterY = this.cutNum(this.rect.top+this.centerY+window.pageYOffset);

		/* マウスの座標の更新 */
		this.getMouseAngle();

		/* 半径の増加量の更新 */
		this.increaseRadius();


	}
	/* 描画メソッド。この中に色んな描画処理をまとめて入れる。 */
	draw()
	{
		// デバッグが必要なら下記をアンコメント
		this.drawDebug();
		switch( this.type )
		{
			case 0:
				this.drawCircleGraph();
				this.drawText();
				break;
			case 1:
				this.drawCircleGraph();
				this.drawCenterCircle();
				this.drawText();
				break;
			default:
				this.drawCircleGraph();
				this.drawText();
				break;
		}
		// this.drawMouseText();
	}
	/* 更新と描画メソッドを入れる。 */
	mainLoop()
	{
		this.update();
		this.draw();
	}




	/****************************************************
	 * 画面クリア
	****************************************************/
	static clear(canvas_id)
	{
		let can = document.getElementById(canvas_id); 
		let con = can.getContext('2d');
		con.clearRect(0, 0, can.width, can.height);
	}




	/****************************************************
	 * 計算関数定義
	****************************************************/
	// 少数第一位までで切り捨てるメソッド
	cutNum(number)
	{
		return Math.round(number * 10)/10;
	}

	// 角度を入力してラジアンを得る。
	getRadian(deg)
	{
		return deg * (Math.PI / 180 );
	}






	/* デバッグ用のテキストを表示。draw()メソッドに入れる。 */
	drawDebug()
	{
	let debug_text = [
		// ["角度",this.getMouseAngle()],
		["増加度",this.increase],
		["フラグ",this.hit_flag],
		["hit",this.hitted],
		["edge",this.edge_flag],
		["sqrt_xy",this.sqrt_xy],
		["sum_of_sector_value",this.sum_of_sector_value],
		["this.angles",this.angles],
		["this.startAngles",this.startAngles],
		["this.finishAngles",this.finishAngles],
		["this.halfDegrees",this.halfDegrees],
		["sum_of_sector_val",typeof this.sum_of_sector_value],
	];
		// 事前準備
		this.con.globalAlpha = 1;
		this.con.font="16px 'ＭＳ　ゴシック'";// フォントを指定。
		this.con.fillStyle = "#000";// 色を指定
		// ここから実際に描画。
		this.con.fillText("pageX,Y:("+px+","+py+")", this.centerX - this.radius, this.centerY + this.radius + 40);
		this.con.fillText("pxxxx:("+px+","+py+")", this.centerX - this.radius, this.centerY + this.radius + 100);
		this.con.fillText(
			"elem + center + scroll:("+this.cutNum(this.rect.left+this.centerX+window.pageXOffset)+
			","+this.cutNum(this.rect.top+this.centerY+window.pageYOffset)+")",
			this.centerX - this.radius, this.centerY + this.radius + 60);

		for( let i=0; i<this.sectorInfo.length; i++ )
		{
			this.con.fillText("Info:"+this.sectorInfo[i], this.centerX - this.radius, this.centerY - this.radius - i*20-100);
		}
		for (let i=0; i<this.angles.length; i++)
		{
			this.drawDebugText("angles", this.angles[i], this.centerY - this.radius - i*20-130);
		}

		
		for ( let i=0; i<debug_text.length; i++)
		{
			this.drawDebugText(debug_text[i][0], debug_text[i][1], this.centerY - this.radius + i * 30 )
		}
	}

	drawDebugText(text, value, position)
	{
		this.con.fillText(text + ": " +  value, this.centerX - this.radius - 300, position );
	}






	/****************************************************
	 * 円グラグ計算メソッド
	 ****************************************************/
	/* 項目の各量を足して合計を求めるメソッド。this.sum_of_sector_valueプロパティに入れる */
	setQuantities()
	{
		this.sum_of_sector_value = 0; //　各項目の量の合計を保持するプロパティ
		this.sectorInfo.forEach( (sect, index) => {
			this.sum_of_sector_value += sect[2];// 量を加算していく。
		});
	}

	/* 配列の初期化 */
	init_array()
	{
		for( let i=0; i<this.sectorInfo.length; i++)
		{
			this.hit_flag.push(0);
			this.increase.push(0);
			this.hitted.push(0);
		}
	}


	// 扇の開始角・終了角を配列に格納するメソッド。
	setStartFinishAngles()
	{
		let angle_sum = 0;
		this.startAngles = [];
		this.finishAngles = [];
		for( let i=0; i<this.angles.length; i++)
		{
			if( i === 0 )
			{
				this.startAngles.push(0)
				this.finishAngles.push(this.angles[i]);
			}
			else
			{
				this.startAngles.push(this.cutNum(angle_sum))
				this.finishAngles.push(this.cutNum(angle_sum+this.angles[i]));
			}
			angle_sum += this.angles[i];
		}
	}


	// 各扇の角度を計算して配列に格納。
	setEachAngles()
	{
		this.angles = [];
		this.sectorInfo.forEach( (sect, index) => {
			this.angles.push( Math.round( sect[2]/this.sum_of_sector_value * 1000 * 3.6)/10);
		});

		// 配列に格納した角度を全て足して360になるか一応確認。
		let sum = 0;
		this.angles.forEach( (sect, index) => {
			sum += sect;
		});
	}



	/* 扇を一つだけ描画するメソッド。これをループで回して円グラグを作る */
	drawSector(start, finish, color, increase)
	{
		// 扇を描画
		this.con.beginPath();// パスで描画するということを宣言。

		this.con.arc(
			this.centerX, this.centerY, this.radius+ increase,// x座標、y座標、半径、
			(start-90)*Math.PI/180,// 開始角
			(finish-90)*Math.PI/180, false)// 終了角
		this.con.lineTo(this.centerX, this.centerY);
		this.con.fillStyle = color;// 色を指定
		this.con.fill();

		// 扇の縁を白で描画。各扇に隙間が空いているように見える。
		if (this.edge_flag)
		{
			this.con.beginPath();
			this.con.arc(
				this.centerX, this.centerY, this.radius + increase,// x座標、y座標、半径、
				(start-90)*Math.PI/180,// 開始角
				(finish-90)*Math.PI/180, false)// 終了角
			this.con.lineTo(this.centerX, this.centerY);
			this.con.lineWidth = 1;// 扇の縁の線の太さ
			this.con.closePath();
			this.con.strokeStyle = "#fff";
			this.con.stroke();
		}
	}

	// ドーナツ型グラフを作成したい場合はこのメソッドで中心に白い円を描き塗りつぶす。
	drawCenterCircle()
	{
		this.con.globalAlpha = 1;
		this.con.beginPath();
		this.con.arc(
			this.centerX,		 // x座標
			this.centerY,		 // y座標
			this.radius/2,	 // 半径
			// 2,	 // 半径
			(0)*Math.PI/180, // 開始角
			(360)*Math.PI/180, false); // 終了角
		this.con.fillStyle = "#fff";
		this.con.closePath();
		this.con.fill();
	}

	/* 円グラフを描画するメソッド。ループで各セクターを描画して円グラフを形成 */
	drawCircleGraph()
	{
		for( let i=0; i<this.sectorInfo.length; i++ )
		{
			// 半径よりも内側にある時、
			if( this.sqrt_xy < this.radius )
			{
				// そのうち扇のどれかの中にマウスがある時。
				if( this.hit( this.startAngles[i], this.finishAngles[i]) )
				{
					this.con.globalAlpha = 1;
					this.hitted[i] = 1;
				}
				else
				{
					this.con.globalAlpha = 0.3;
					this.hitted[i] = 0;
				}
			}
			// 半径の外側にある時
			else 
			{
					this.con.globalAlpha = 1;
					this.hitted[i] = 0;
			}
			this.drawSector(this.startAngles[i], this.finishAngles[i], this.sectorInfo[i][0], this.increase[i]);
		}
	}

	drawMouseText(sectorItem)
	{
		for( let i=0; i<this.hitted.length; i++)
		{
			this.con.font = "20px Arial, meiryo";
			let textSize = this.con.measureText(this.sectorInfo[i][1]);
			if(this.hitted[i])
			{
				this.con.fillStyle = "#000";// 色を指定
				this.con.fillRect(ox, oy-20, textSize.width, 20);
				this.con.fillStyle = "#fff";// 色を指定
				this.con.fillText(this.sectorInfo[i][1], ox, oy-4);
			}
			else if(!this.hitted[i]) {
			}
			this.con.fillStyle = "#000";// 色を指定
			this.con.fillText("hit:"+this.hitted, this.centerX - this.radius, this.centerY - this.radius - 70);
		}
	}


	/* 扇の中に表示するパーセンテージの位置を計算 */
	setHalfDegrees()
	{
		this.halfDegrees = [];
		let deg = 0;// 角度
		let rad = 0;// ラジアン
		let sin = 0;// サイン
		let cos = 0;// コサイン
		let halfTextSize = 0;// テキストサイズのオブジェクトを格納する
		
		/* 各項目のパーセンテージを個別の取り出したいのでループさせる */
		for( let i = 0; i<this.sectorInfo.length; i++)
		{
			this.con.font = "12px Arial, meiryo";

			/* measureTextは文字サイズ情報をオブジェクトで返す関数。
			 * 使用する前にcontext.fontでフォントを指定する必要がある。
			 * 文字の幅と%を足した幅を変数に格納 */
			halfTextSize = this.con.measureText(this.cutNum(this.sectorInfo[i][2]/this.sum_of_sector_value*100)+"%");
			/* 各扇の中間の角度を0-360度まで計算 */
			deg = this.cutNum(this.startAngles[i]+(this.finishAngles[i] - this.startAngles[i])/2);// 円の角度
			sin = Math.sin(this.getRadian(deg));
			cos = Math.cos(this.getRadian(deg));


			this.half_degree_x = this.cutNum(this.radius * sin);
			this.half_degree_y = this.cutNum(this.radius * cos);
			if( deg > 0 && deg < 90 ){ if(this.half_degree_x<0)this.half_degree_x=-this.half_degree_x;if(this.half_degree_y>0)this.half_degree_y=-this.half_degree_y;}
			else if( deg > 90 && deg < 180 ){ if(this.half_degree_x<0)this.half_degree_x=-this.half_degree_x;if(this.half_degree_y<0)this.half_degree_y=-this.half_degree_y;}
			else if( deg > 180 && deg < 270 ){if(this.half_degree_x>0)this.half_degree_x=-this.half_degree_x;if(this.half_degree_y<0)this.half_degree_y=-this.half_degree_y;}
			else if( deg > 270 && deg < 360 ){if(this.half_degree_x>0)this.half_degree_x=-this.half_degree_x;if(this.half_degree_y>0)this.half_degree_y=-this.half_degree_y;}

			this.half_degree_x *= 0.8;
			this.half_degree_y *= 0.8;

			/* 中心座標+上で求めた半分の角度の円周上の座標-文字サイズ/2 */
			this.half_degree_x = this.cutNum(this.centerX+this.half_degree_x-halfTextSize.width/2);
			this.half_degree_y = this.cutNum(this.centerY+this.half_degree_y);

			/* この操作非常に重要。
			 * 一旦x,yの座標を入れた配列を作り、
			 * それを別の配列にプッシュしていけば二次元配列が出来る！ */
			let array = [this.half_degree_x, this.half_degree_y];
			this.halfDegrees.push(array);
		}
	}






	/*********************************************
	 * 100％の時、扇のエッジを消す判定
	*********************************************/
	checkSectorValueForEdge()
	{
		let edge_count = 0;
		this.edge_flag = 1;
		for (let i=0; i<this.sectorInfo.length; i++)
		{
			if (this.sectorInfo[i][2])
			{
				edge_count++;
			}
		}

		if (edge_count === 1)
		{
			this.edge_flag = 0;
		}
	}



	// 項目などの情報を描画
	drawText()
	{
		this.con.globalAlpha = 1;
		this.con.font="12px 'ＭＳ　ゴシック'";// フォントを指定。
		this.con.fillStyle = "#000";// 色を指定
		for( let i=0; i<this.sectorInfo.length; i++ )
		{
			this.con.fillStyle = "#000";// 色を指定
			// this.con.fillStyle = "#fff";// 色を指定

			if (this.sectorInfo[i][2])
			{
				this.con.fillText(
					this.cutNum(this.sectorInfo[i][2]/this.sum_of_sector_value*100)+"%", 
					this.halfDegrees[i][0], 
					this.halfDegrees[i][1]
				);
				/*
				this.drawDebugText(
					"this.sectorInfo[i][2]/this.sum_of_sector_value",
					this.sectorInfo[i][2]/this.sum_of_sector_value, 
					this.centerY - this.radius + (i+10) * 30
				);
				*/
			}
		}
	}


	/* 各円グラグとマウスとの角度を求めるメソッド */
	/* 中心座標から上を0、下を180度となるように計算する */
	getMouseAngle()
	{
		// まずマウスと中心座標の距離を計算する。
		let abs_x = 0;
		let abs_y = 0;
		abs_x = Math.abs(px - this.absoluteCenterX);// マウスと中心座標のx座標を絶対値で取得
		abs_y = Math.abs(py - this.absoluteCenterY);// マウスと中心座標のy座標を絶対値で取得
		// xとyと中心座標との平方根を求める
		this.sqrt_xy = Math.floor(Math.sqrt(abs_x*abs_x+abs_y*abs_y));

		// 各円グラフとマウスの角度を計算して求める。
		this.angle = Math.floor(Math.atan2( abs_y, abs_x ) * 180/Math.PI);
		// this.degree = this.angle;

		// このままでは値がおかしいので自然な角度に修正。
		if( px > this.absoluteCenterX && py < this.absoluteCenterY) this.angle = 90 - this.angle;
		else if( px > this.absoluteCenterX && py === this.absoluteCenterY) this.angle = 90;
		else if( px > this.absoluteCenterX && py > this.absoluteCenterY) this.angle = 90 + this.angle;
		else if( px === this.absoluteCenterX && py > this.absoluteCenterY) this.angle = 180;
		else if( px < this.absoluteCenterX && py > this.absoluteCenterY) this.angle = 270 - this.angle;
		else if( px < this.absoluteCenterX && py === this.absoluteCenterY) this.angle = 270;
		else if( px < this.absoluteCenterX && py < this.absoluteCenterY) this.angle = 270 + this.angle;
		else { this.angle = 0;}
		return this.angle;
	}


	/* あたり判定 */
	hit( start, finish )
	{
		// 半径より内側にマウスが合ったら、角度が扇のどれかの範囲に該当していたら。
		if( this.sqrt_xy < this.radius && this.angle >= start && this.angle < finish )
		{
			return true;
		}
		return false;
	}

	/* マウスオーバーで跳ねるアニメーション */
	increaseRadius()
	{
		for( let i=0; i<this.sectorInfo.length; i++ )
		{
			// this.hittedはそれぞれの扇がヒットしているか真偽値で保存しておく配列。
			if(this.hitted[i] && !this.hit_flag[i] && this.increase[i]<10)
			{
				this.increase[i]++;
				this.increase[i]++;
				if(this.increase[i] > 9 ) this.hit_flag[i] = 1;
			}
			else if( !this.hitted[i] )
			{
				this.increase[i] = 0;
				this.hit_flag[i] = 0;
			}

			if( this.hit_flag[i] && this.increase[i] > 5 )
			{
				this.increase[i]--;
				this.increase[i]--;
			}
		}
	}

}// Circleクラスのとじカッコ
