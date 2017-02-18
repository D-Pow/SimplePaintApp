var canvas = document.getElementById("whiteboard");
var context = canvas.getContext("2d");
var cwidth = parseInt(getComputedStyle(canvas).getPropertyValue('width'));
var cheight = parseInt(getComputedStyle(canvas).getPropertyValue('height'));

var paint; //Boolean monitoring if it should paint
var lineStarted; //if we've started drawing a line
//Allow mobile users to use the app, too
var touchMode = !!(navigator.userAgent.toLowerCase().match(/(android|iphone|ipod|ipad|blackberry)/));

context.lineWidth = 5;
context.strokeStyle = "black";
context.fillStyle = "#424242";

//Set event listeners to correct event (touch vs mouse-click)
var downEvent = touchMode ? 'touchstart' : 'mousedown';
var moveEvent = touchMode ? 'touchmove' : 'mousemove';
var upEvent   = touchMode ? 'touchend' : 'mouseup';
//Event listeners
canvas.addEventListener(downEvent, mouseDown);
canvas.addEventListener(moveEvent, mouseMove);
canvas.addEventListener(upEvent  , mouseUp);

//The mouse is clicked down
function mouseDown(event) {
    mouse.x = event.pageX - this.offsetLeft;
    mouse.y = event.pageY - this.offsetTop;
    alert("X= " + mouse.x + "; Y= " + mouse.y);

    paint = true;
    //addClick(event.pageX - this.offsetLeft, event.pageY - this.offsetTop);
    //redraw();
}

//The mouse moves
function mouseMove(event) {
    event.preventDefault(); //necessary for early versions of Android
    if (!paint) {
        return;
    }
    
    var pos = touchMode ? getTouchPos(event) : getMousePos(event);
    
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
    var rect = canvas.getBoundingClientRect();
    return {
        x: event.clientX - rect.left,
        y: event.clientY - rect.top
    };
}

//The mouse is lifted
function mouseUp(event) {
    event.preventDefault(); //for early Android
    paint = false;
    lineStarted = false;
}

//Mouse leaves the canvas
function mouseLeave(event) {
    paint = false;
}