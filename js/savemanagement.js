/**
 * Saves the image to the database
 * and displays the result of the request
 * using ajax
 */
function saveSketch() {
    var image = new Image();
    image.src = canvas.toDataURL("image/png", 1.0);

    var sketchid = 1; //to be changed later when multiple sketches is implemented

    $.ajax({
        type: 'POST',
        url: './php/save.php',
        data: {
            sketchid: sketchid,
            sketch:   image.src
        },
        success: function(result) {
            alert(result);
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