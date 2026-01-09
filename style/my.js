function validate_key(evt,tipe="number") {
  var theEvent = evt || window.event;

  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9]|\./;
  if(tipe=='string_only'){
    var regex = /^[a-z]/;
  }else if(tipe=='number_only'){
    var regex = /[0-9]/;
  }else if(tipe=='number_string'){
    var regex = /[0-9]|^[a-z]/;
  }
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
const ch_colors = ["rgb(255, 99, 132)",
  "rgb(55, 99, 132)",
  "rgb(54, 162, 235)",
  "rgb(100, 205, 86)",
  "rgb(255, 100, 86)",
  "rgb(100, 0, 100)",
  "rgb(100, 55, 200)",
  "rgb(65, 205,200)",
  "rgb(100, 205, 255)",
  "rgb(55, 55, 255)",
  "rgb(255, 99, 132)",
  "rgb(55, 99, 132)",
  "rgb(54, 162, 235)",
  "rgb(100, 205, 86)",
  "rgb(255, 100, 86)",
  "rgb(100, 0, 100)",
  "rgb(100, 55, 200)",
  "rgb(65, 205,200)",
  "rgb(100, 205, 255)",
  "rgb(55, 55, 255)",
  "rgb(255, 99, 132)",
  "rgb(55, 99, 132)",
  "rgb(54, 162, 235)",
  "rgb(100, 205, 86)",
  "rgb(255, 100, 86)",
  "rgb(100, 0, 100)",
  "rgb(100, 55, 200)",
  "rgb(65, 205,200)",
  "rgb(100, 205, 255)",
  "rgb(55, 55, 255)",
  "rgb(255, 99, 132)",
  "rgb(55, 99, 132)",
  "rgb(54, 162, 235)",
  "rgb(100, 205, 86)",
  "rgb(255, 100, 86)",
  "rgb(100, 0, 100)",
  "rgb(100, 55, 200)",
  "rgb(65, 205,200)",
  "rgb(100, 205, 255)",
  "rgb(55, 55, 255)",
  "rgb(54, 162, 235)",
  "rgb(100, 205, 86)",
  "rgb(255, 100, 86)",
  "rgb(100, 0, 100)",
  "rgb(100, 55, 200)",
  "rgb(65, 205,200)",
  "rgb(100, 205, 255)",
  "rgb(55, 55, 255)",
];