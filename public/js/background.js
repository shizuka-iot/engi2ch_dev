
const DRAWIMAGE_W = 80;
const MAX_IMGS = 46;

function rand(min,max)
{
	return Math.floor( Math.random()*(max-min+1) )+ min;
}
function zeroPadding(num, length)
{
	return ('0000000000' + num).slice(-length);
}

let can = document.getElementById("background_canvas");
let con = can.getContext("2d");
can.width = window.innerWidth;
can.height = window.innerHeight;


class Logo
{
	constructor(src_path)
	{
		this.img = new Image();
		this.img.src = src_path;
		this.imgH = Math.round(DRAWIMAGE_W * (this.img.height/this.img.width));
		this.x = rand(0, innerWidth)<<8;
		this.y = rand(0, innerHeight)<<8;
		this.vx = 0;
		this.vy = rand(100, 300);
	}

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

	update()
	{
		this.x -= this.vx;
		this.y -= this.vy;
		if (this.y < -this.imgH <<8)
		{
			this.y = this.imgH + innerHeight <<8;
			this.x = rand(0, innerWidth) <<8;
		}

		this.draw();
	}
}


let imgs = [];
let img_paths = [];
function init()
{
	for (let i=0; i<MAX_IMGS; i++)
	{
		img_paths[i] = "../logo/" + zeroPadding(i+1, 3) + ".png";
	}
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i] = new Logo(img_paths[i]);
	}
}

window.addEventListener('resize', function(e)
	{
		can.width = window.innerWidth;	
		can.height = window.innerHeight;	
		init();
	});

function backgroundUpdate()
{
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i].update();
	}
}

function backgroundDraw()
{
	for (let i=0; i<img_paths.length; i++)
	{
		imgs[i].draw();
	}
}
init();

/*
window.onload = function mainLoop()
{
	requestAnimationFrame(mainLoop);
	con.clearRect(0, 0, innerWidth, innerHeight);
	backgroundUpdate();
	backgroundDraw();
}
*/
