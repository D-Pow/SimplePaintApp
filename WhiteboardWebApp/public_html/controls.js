//context defined in drawing

//Div element containing the control panel
var controlDiv = document.getElementById('controls');

//Color input element
var colorInput = document.getElementById("color-chooser");
colorInput.addEventListener("input", updateColor);

//Stroke-size input element
var strokeSizeInput = document.getElementById("stroke-width-chooser");
strokeSizeInput.addEventListener("input", updateStrokeSize);

/**
 * Updates stroke color based on the color-input element.
 */
function updateColor() {
    context.strokeStyle = colorInput.value;
}

function updateStrokeSize() {
    console.log(strokeSizeInput.value);
    document.getElementById("rangeValue").value = strokeSizeInput.value;
    context.lineWidth = strokeSizeInput.value;
}

/**
 * Shows/hides control panel.
 */
function toggleControls() {
    controlDiv.classList.toggle('hidden');
}