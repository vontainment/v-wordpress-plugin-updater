function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

window.onload = function () {
  // Get the hash from the URL (if exists)
  var hash = window.location.hash.substr(1);

  // Open the tab if hash exists in URL
  if (hash) {
    openTab(event, hash);
  } else {
    // Else, open the first tab
    document.querySelector(".tablinks").click();
  }
};