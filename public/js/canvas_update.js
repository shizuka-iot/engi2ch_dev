let circle = null;
if (typeof sectorInfo !== 'undefined')
{
	circle = new Circle('can', sectorInfo, 80 ,100, 90, 0, 100);

	function mainLoop()
	{
		requestAnimationFrame(mainLoop);
		circle.update();
		Circle.clear('can');
		circle.draw();
		con.clearRect(0, 0, innerWidth, innerHeight);
		backgroundUpdate();
		backgroundDraw();
	}

	window.onload = function() 
	{
		mainLoop();
	}
}
else
{
	window.onload = function mainLoop()
	{
		requestAnimationFrame(mainLoop);
		con.clearRect(0, 0, innerWidth, innerHeight);
		backgroundUpdate();
		backgroundDraw();
	}
}

