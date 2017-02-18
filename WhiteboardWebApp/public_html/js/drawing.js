var canvas = document.getElementById("whiteboard");
var context = canvas.getContext("2d");
changeCanvasSize();  //update canvas to current window size

var paint; //Boolean monitoring if it should paint
var lineStarted; //if we've started drawing a line

//Allow mobile users to use the app, too
var touchMode = !!(navigator.userAgent.toLowerCase().match(/(android|iphone|ipod|ipad|blackberry)/));

//Default values
context.lineWidth = 1;
context.strokeStyle = "black";

//Set event listeners to correct event (touch vs mouse-click)
var downEvent = touchMode ? 'touchstart' : 'mousedown';
var moveEvent = touchMode ? 'touchmove' : 'mousemove';
var upEvent   = touchMode ? 'touchend' : 'mouseup';
//Event listeners
canvas.addEventListener(downEvent, mouseDownFunc);
canvas.addEventListener(moveEvent, mouseMoveFunc);
canvas.addEventListener(upEvent  , mouseUpFunc);
canvas.addEventListener('mouseleave', mouseLeaveFunc);  //if mouse leaves sketch area
window.addEventListener('resize', changeCanvasSize);  //if window changes, resize canvas

//The mouse is clicked down
function mouseDownFunc(event) {
    paint = true;
}

//The mouse moves
function mouseMoveFunc(event) {
    //test coordinates
    /*
    var p = document.getElementById("coords");
    var pos = getMousePos(event);
    p.innerHTML = ("X: " + Math.round(pos.x) + ";   Y: " + Math.round(pos.y));
    */
    event.preventDefault(); //necessary for early versions of Android
    if (!paint) {
        return;
    }
    
    var pos = touchMode ? getTouchPos(event) : getMousePos(event);
    
    //Draw the line
    if (!lineStarted) {
        context.beginPath();
        context.moveTo(pos.x, pos.y);
        lineStarted = true;
    } else {
        context.lineTo(pos.x, pos.y);
        context.stroke();
    }
}

function getMousePos(event) {
    //offset is actual spot on the page
    //boundingClient is relative to the user's view
    //e.g. scrolling down the page changes the boundingClient.top
    //     but doesn't change offsetTop
    return {
        x: event.pageX - canvas.offsetLeft,
        y: event.pageY - canvas.offsetTop
    };
}

function getTouchPos(event) {
    var rect = canvas.getBoundingClientRect();
    var touch = event.targetTouches[0];
    return {
        x: touch.pageX - rect.left,
        y: touch.pageY - rect.top
    };
}

//The mouse is lifted
function mouseUpFunc(event) {
    event.preventDefault(); //for early Android
    paint = false;
    lineStarted = false;
}

//Mouse leaves the canvas
function mouseLeaveFunc(event) {
    paint = false;
    lineStarted = false;
}

/**
 * Automatically updates canvas size according
 * to window size.
 */
function changeCanvasSize() {
    //Make canvas 70% of the window size
    var widthPercent = 0.7;
    var heightPercent = 0.7;
    canvas.width = window.innerWidth*widthPercent;
    canvas.height= window.innerHeight*heightPercent;
}