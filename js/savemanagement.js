function saveSketch() {
    var image = new Image();
    image.src = canvas.toDataURL("image/png", 1.0);
    
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', './php/save.php');
    
    var data = document.createElement('input');
    data.setAttribute('type', 'hidden');
    data.setAttribute('value', image);

    //Submit form to save.php, but redirect user to blank iframe to 
    //let them stay on the drawing page
    var hiddenFrame = document.getElementById('saveFormAccept');
    form.setAttribute('target', hiddenFrame.getAttribute('name'));

    form.appendChild(data);
    document.body.appendChild(form);
    form.submit();
    alert("Sketch saved!");
}