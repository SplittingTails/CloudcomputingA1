function myFunction(elementID) {
  let elementreview = document.getElementById('review'+elementID);
  let elementedit = document.getElementById('edit'+elementID);
  if (elementreview.style.display === "none") {
    elementreview.style.display = "block";
    elementedit.style.display = "none";
} else {
  elementreview.style.display = "none";
    elementedit.style.display = "block";
}
  }