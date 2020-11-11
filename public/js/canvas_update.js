let circle = null;
if (typeof sectorInfo !== 'undefined')
{
	circle = new Circle('can', sectorInfo, 80 ,100, 100, 0, 100);

	function mainLoop()
	{
		requestAnimationFrame(mainLoop);
		circle.update();
		Circle.clear('can');
		circle.draw();
	}

	window.onload = function() 
	{
		mainLoop();
	}
}
