//context defined in drawing

//Div element containing the control panel
var controlDiv = document.getElementById('controls');

//Color input element
var colorInput = document.getElementById("color-chooser");
colorInput.addEventListener("input", updateColor);

//Stroke-size input element
var strokeSizeInput = document.getElementById("stroke-width-chooser");
strokeSizeInput.addEventListener("input", updateStrokeSize);
updateStrokeSize();  //call to update current stroke size upon init

/**
 * Updates stroke color based on the color-input element.
 */
function updateColor() {
    context.strokeStyle = colorInput.value;
}

/**
 * Update width of stroke line.
 */
function updateStrokeSize() {
    document.getElementById("rangeValue").innerHTML = Math.round(strokeSizeInput.value);
    context.lineWidth = strokeSizeInput.value;
}

/**
 * Shows/hides control panel.
 */
function toggleControls() {
    controlDiv.classList.toggle('hidden');
}

/**
 * Clears drawing canvas.
 */
function clearCanvas() {
    var canvasRect = canvas.getBoundingClientRect();
    context.clearRect(0, 0, canvasRect.width, canvasRect.height);
}