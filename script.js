// const tabItems = document.querySelectorAll(".tab-item"); // Sélectionne tous les éléments avec la classe 'tab-item'
// const tabContentItems = document.querySelectorAll(".tab-content-item"); // Sélectionne tous les éléments avec la classe 'tab-content-item'


// function selectItem(e) {
//   removeBorder(); //Appelle la fonction removeBorder()
//   removeShow(); //Appelle la fonction removeShow()
//   tabContentItems.classList.add("show"); // Ajoute la classe 'show' à cet élément
// }

// function removeBorder() {
//     //parcourir tous les éléments de tabItems
//   tabItems.forEach((item) => {
//     item.classList.remove("tab-border"); // Supprime la classe 'tab-border' de chaque élément
//   });
// }

// function removeShow() {
//     //parcourir tous les éléments de tabContentItems
//   tabContentItems.forEach((item) => {
//     item.classList.remove("show"); // Supprime la classe 'show' de l'élément
//   });
// }

// // on execute la fonction selectItem() lorsque on ecoute un evenement (click) sur chaque element du tabItems
// tabItems.forEach((item) => {
//   item.addEventListener("click", selectItem); 
// });
const tabItems = document.querySelectorAll('.tab-item');
const tabContentItems = document.querySelectorAll('.tab-content-item');
// Select tab content item
function selectItem(e) {
  // Remove all show and border classes
  removeBorder();
  removeShow();
  // Add border to current tab item
  this.classList.add('tab-border');
  // Grab content item from DOM
  const tabContentItem = document.querySelector(`#${this.id}-content`);
  // Add show class
  tabContentItem.classList.add('show');
}
// Remove bottom borders from all tab items
function removeBorder() {
  tabItems.forEach(item => {
    item.classList.remove('tab-border');
  });
}
// Remove show class from all content items
function removeShow() {
  tabContentItems.forEach(item => {
    item.classList.remove('show');
  });
}
// Listen for tab item click
tabItems.forEach(item => {
  item.addEventListener('click', selectItem);
});