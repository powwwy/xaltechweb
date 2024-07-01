// Get the header element
var header = document.querySelector("header");

// Get the offset position of the header
var sticky = header.offsetTop;

// Function to add the "sticky" class to the header when scrolling
function makeHeaderSticky() {
  if (window.scrollY > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}

// trigger the sticky header
window.addEventListener("scroll", makeHeaderSticky);

function sendEmail(){
  var recipient = "info@xalal.tech";
  var subject ="Inquiry";
  var body = " ";
  var mailtoLink = "mailto: " + recipient + "?subject=" + subject +"&body="+ body;
  window.location.href = mailtoLink;
}
