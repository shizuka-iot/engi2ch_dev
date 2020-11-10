if (typeof sectorInfo !== 'undefined')
{
	let circle = new Circle('can', sectorInfo, 80 ,100, 90, 0, 100);

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
