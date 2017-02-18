//context defined in drawing

//Color input element
var colorInput = document.getElementById("color-chooser");
colorInput.addEventListener("click", updateColor); //resets color if user doesn't change color
colorInput.addEventListener("input", updateColor); //changes color if user changes color

//Stroke-size input element
var strokeSizeInput = document.getElementById("stroke-width-chooser");
strokeSizeInput.addEventListener("input", updateStrokeSize);
updateStrokeSize();  //call to update current stroke size upon init

//Set width of toggle-controls button to be static
var controlsButton = document.getElementById("toggleControlsButton");
//offsetWidth would work, too, but boundingClientRect is more accurate
tableWidth = document.getElementById("controlsTable").getBoundingClientRect().width;
controlsButton.style.width = tableWidth + "px";

/**
 * Updates stroke color based on the color-input element.
 * Updates stroke size, as well, to counteract the effects
 * of clicking the eraser button.
 */
function updateColor() {
    context.strokeStyle = colorInput.value;
    updateStrokeSize(); //sets pen back to chosen size if it was not already set
}

/**
 * Update width of stroke line.
 */
function updateStrokeSize() {
    var v = strokeSizeInput.value;               //actual value
    var p = Math.round(strokeSizeInput.value);   //value to be printed
    //format single digits to be same width as double digits
    document.getElementById("rangeValue").innerHTML = p < 10 ? " " + p : p;
    context.lineWidth = v;
}

/**
 * Shows/hides control panel.
 */
function toggleControls() {
    var table = document.getElementById("controlsTable");
    var elemsToHide = table.querySelectorAll("tr");
    for (var i = 0; i < elemsToHide.length; i++) {
        elemsToHide[i].classList.toggle('hidden');
    }
}

/**
 * Clears drawing canvas.
 */
function clearCanvas() {
    var canvasRect = canvas.getBoundingClientRect();
    context.clearRect(0, 0, canvasRect.width, canvasRect.height);
}

/**
 * Selects the white color to serve as an eraser.
 * Slightly enlarges the eraser size for ease of use
 */
function selectEraserColor() {
    context.strokeStyle = "#FFFFFF";
    context.lineWidth = context.lineWidth + 5;
}