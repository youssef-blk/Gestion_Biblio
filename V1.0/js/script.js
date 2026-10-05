function $(id) {
  return document.getElementById(id);
}

function fillFormFromRow(row) {
  const formLivre = $("formLivre");
  const formBtn = $("formBtn");
  const idLivreInput = $("id_livre");
  const titreInput = $("titre");
  const auteurInput = $("auteur");
  const categorieInput = $("id_categorie");
  const isbnInput = $("isbn");
  const anneeInput = $("annee_publication");
  const quantiteInput = $("quantite");

  const cells = row.querySelectorAll("td");
  
  idLivreInput.value = row.dataset.bookId || cells[0].innerText.trim() || "";
  titreInput.value = cells[1].innerText.trim();
  auteurInput.value = cells[2].innerText.trim();

  const rawIsbn = cells[4].innerText.trim();
  isbnInput.value = rawIsbn === "-" ? "" : rawIsbn;

  const rawAnnee = cells[5].innerText.trim();
  anneeInput.value = rawAnnee === "-" ? "" : rawAnnee;

  quantiteInput.value = cells[6].innerText.trim();

  const categoryId = row.dataset.categoryId;
  if (categoryId) {
    categorieInput.value = categoryId;
  } else {
    const categoryText = cells[3].innerText.trim();
    let categoryFound = false;

    Array.from(categorieInput.options).forEach((option) => {
      if (option.text.trim() === categoryText) {
        categorieInput.value = option.value;
        categoryFound = true;
      }
    });

    if (!categoryFound) {
      categorieInput.value = "";
    }
  }

  formLivre.action = "api/moudifier_livre.php";
  formBtn.innerHTML = `
    <i class="fa-solid fa-pen-to-square"></i>
    <span>Modifier le livre</span>
  `;
  formLivre.scrollIntoView({ behavior: "smooth", block: "start" });
}

document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".btn-edit").forEach((button) => {
    button.addEventListener("click", function () {
      const row = this.closest("tr");
      if (row) {
        fillFormFromRow(row);
      }
    });
  });

  const formLivre = $("formLivre");
  if (formLivre) {
    formLivre.addEventListener("reset", () => {
      $("id_livre").value = "";
      formLivre.action = "api/ajouter_livre.php";
      const formBtn = $("formBtn");
      if (formBtn) {
        formBtn.innerHTML = `
          <i class="fa-solid fa-plus"></i>
          <span>Ajouter au catalogue</span>
        `;
      }
    });
  }
});
