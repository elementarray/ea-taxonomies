//

document.body.onload = ( function(){ removeElement(document.getElementById('normal-sortables')); } )()

//addElement( document.getElementsByClassName('wrap')[0].parentNode );
// Usage:
// addElement( document.getElementsByClassName('wrap') );
function addElement (element) { 
  // create new element 
  let newEl = document.createElement("div"); 
  // content 
  let newContent = document.createTextNode("Hi there and greetings!"); 
  // attach content to new element
  newEl.appendChild(newContent);  
  // insert new element/content into target point (parent element node)
  element.appendChild(newEl); 
}


// Usage:
// removeElement( document.getElementById( 'post' ) );
function removeElement(element) {
    element && element.parentNode && element.parentNode.removeChild(element);
}



