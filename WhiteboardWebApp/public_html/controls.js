//context defined in drawing
var colorInput = document.getElementById("color-chooser");
colorInput.addEventListener("input", updateColor);

/**
 * Updates stroke color based on the color-input element.
 */
function updateColor() {
    context.strokeStyle = colorInput.value;
}