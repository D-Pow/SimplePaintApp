/**
 * Loads the sketch with the corresponding sketchid
 * from the database using ajax
 */
function loadSketch() {
    var newImage = new Image();

    if (confirm("Do you want to discard your current sketch and load the old one?")) {
        $.ajax({
            method: 'POST',
            url: './php/load.php',
            success: function(result) {
                //If not in database
                if (result == 'not present') {
                    alert('That sketch does not exist.');
                //If there was a problem loading the sketch
                } else if (result == "problem loading") {
                    alert("There was a problem loading your sketch.")
                //If loaded successfully
                } else {
                    newImage.src = result;
                    //Clear canvas, then replace it with saved sketch
                    clearCanvas();
                    newImage.onload = function() {
                        context.drawImage(newImage, 0, 0, canvas.width, canvas.height);
                    }
                }
            }
        });
    }
}