/**
 * Saves the image to the database
 * and displays the result of the request
 * using ajax
 */
function saveSketch() {
    var image = new Image();
    image.src = canvas.toDataURL("image/png", 1.0);

    $.ajax({
        method: 'POST',
        url: './php/save.php',
        data: {
            sketch:   image.src
        },
        success: function(result) {
            //If cookie was tampered with
            if (result == 'bad cookie') {
                alert('Cookie tampered with. Aborting.');
            }
            //If sketch already present, offer to overwrite it
            else if (result=='validate') {
                var replace = confirm("Do you wish to overwrite your old sketch?");
                if (replace) {
                    //overwrite the sketch
                    $.ajax({
                        method: 'POST',
                        url: './php/save.php',
                        data: {
                            sketch:   image.src,
                            replace:  true
                        },
                        success: function(result) {
                            if (result == 'replaced') {
                                alert("Sketch overwritten!");
                            } else {
                                alert("Something went wrong, and your sketch wasn't saved.");
                            }
                        }
                    });
                }
            //If sketch wasn't already present and the database tried to insert it
            } else if (result == 'inserted') {
                alert("Sketch saved!");
            } else if (result == 'not inserted') {
                alert("Something went wrong, and your sketch wasn't saved.");
            }
        }
    });
}

/**
 * Same save function using JavaScript
 * which doesn't allow seeing the result of save.php
 */
function saveJS() {
    var image = new Image();
    image.src = canvas.toDataURL("image/png", 1.0);
    
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', './php/save.php');
    form.setAttribute('id', 'saveForm');
    
    var imgData = document.createElement('input');
    imgData.setAttribute('type', 'hidden');
    imgData.setAttribute('name', 'sketch');
    imgData.setAttribute('value', image.src);

    var skchData = document.createElement('input');
    skchData.setAttribute('type', 'hidden');
    skchData.setAttribute('name', 'sketchid');
    skchData.setAttribute('value', 1);

    //Submit form to save.php, but redirect user to blank iframe to 
    //let them stay on the drawing page
    var hiddenFrame = document.getElementById('saveFormAccept');
    form.setAttribute('target', hiddenFrame.getAttribute('name'));

    form.appendChild(imgData);
    form.appendChild(skchData);
    document.body.appendChild(form);
    form.submit();
    //alert("Sketch saved!");
}