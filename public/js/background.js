
// ロゴの横幅を指定
const DRAWIMAGE_W = 80;
// logoフォルダの中のファイル数
const MAX_IMGS = 46;

// Logoクラスのインスタンスを入れる配列
let imgs = [];
// ロゴのパスを入れる配列
let img_paths = [];

// 2回目のinit関数呼び出しでtrueに変える。
// trueならglobalAlphaを加算する。
let reboot = false;

// ランダム値生成関数定義
function rand(min,max)
{
	return Math.floor( Math.random()*(max-min+1) )+ min;
}
// 数字の左をゼロで埋める関数定義
function zeroPadding(num, length)
{
	return ('0000000000' + num).slice(-length);
}


// キャンバス初期化
let can = document.getElementById("background_canvas");
let con = can.getContext("2d");
can.width = window.innerWidth;
can.height = window.innerHeight;
con.globalAlpha = 0.0;


// ロゴクラス定義
class Logo
{
	constructor(src_path)
	{
		// ロゴ画像の読み込み
		this.img = new Image();
		this.img.src = src_path;
		this.imgH = Math.round(DRAWIMAGE_W * (this.img.height/this.img.width));
		// ロゴの最初の出現位置
		this.x = rand(0, innerWidth)<<8;
		this.y = rand(0, innerHeight)<<8;

		// ロゴのフレームごとの移動量
		this.vx = 0;
		this.vy = rand(100, 300);
	}

	// ロゴの描画
	draw()
	{
		con.drawImage(
			this.img,
			0,
			0,
			this.img.width,
			this.img.height,
			this.x>>8, 
			this.y>>8,
			DRAWIMAGE_W,
			this.imgH
		);
	}

	// ロゴのフレームごとの位置情報を更新
	update()
	{
		this.x -= this.vx;
		this.y -= this.vy;
		if (this.y < -this.imgH <<8)
		{
			this.y = this.imgH + innerHeight <<8;
			this.x = rand(0, innerWidth) <<8;
		}
	}
}


// ロゴインスタンス作成などの初期化
function init()
{
	can.width = window.innerWidth;	
	can.height = window.innerHeight;	
	con.globalAlpha = 0.0;
	// logoのパスをコンパイル
	for (let i=0; i<MAX_IMGS; i++)
	{
		img_paths[i] = "../logo/" + zeroPadding(i+1, 3) + ".png";
	}
	// Logoクラスのインスタンス化
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i] = new Logo(img_paths[i]);
	}
	if (!reboot)
	{
		// 遅延させてrebootをtrueに。
		setTimeout( function(){
		reboot = true;
		}, 1000);
	}
}


// 画面リサイズで再初期化
window.addEventListener('resize', function(e)
	{
		can.width = window.innerWidth;	
		can.height = window.innerHeight;	
		init();
	});


// backgroundキャンバス全体を更新
function backgroundUpdate()
{
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i].update();
	}
}

// backgroundキャンバス全体を描画
function backgroundDraw()
{
	if (reboot)
	{
		con.globalAlpha += 0.01;
	}
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i].draw();
	}
}
init();

// 苦し紛れの遅延処理
setTimeout(init, 1000);

/*
window.onload = function mainLoop()
{
	requestAnimationFrame(mainLoop);
	con.clearRect(0, 0, innerWidth, innerHeight);
	backgroundUpdate();
	backgroundDraw();
}
*/
