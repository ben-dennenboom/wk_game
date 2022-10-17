    elem = document.getElementById("elemId");
    // where elem is the dom element to watch, Text input only
    // Changed is the callback function, will be called after 1.5s in the example down below
    // 1500 are the ms to wait after the last user keystroke on the elem
// why? because changed event fire continuously after every keystroke, InputtingObserver just wait to the end before calling the callback function
    InputtingObserver(elem, Changed, 1500);
    
    function Changed() {
      alert("input has been changed")
    }
