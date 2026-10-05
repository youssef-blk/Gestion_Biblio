const API_URL = "api/livre_controller.php";

//! Read
async function afficheLivres(query) {
  try {
    const response = await fetch(query === undefined ? API_URL : `${API_URL}?query=${query}`);
    if (!response.ok) {
      throw new Error("Error HTTP : " + response.status);
    }
    const livres = await response.json();

    const table = document.getElementById("livresTable");
    table.innerHTML = "";

    livres.forEach((livre) => {
      const row = document.createElement("tr");
      row.dataset.bookId = livre.id_livre;
      row.dataset.categoryId = livre.id_categorie;
      row.innerHTML = `
        <td><strong>${livre.id_livre}</strong></td>
        <td><div class="book-title">${livre.titre}</div></td>
        <td>${livre.auteur}</td>
        <td><span class="category-tag tag-${livre.id_categorie}">${livre.categorie}</span></td>
        <td>${livre.isbn || "-"}</td>
        <td>${livre.annee_publication || "-"}</td>
        <td><span class="stock-badge ${Number(livre.quantite) > 0 ? "stock-available" : "stock-low"}">${livre.quantite}</span></td>
        <td class="text-center actions-cell">
          <button type="button" class="btn-icon btn-edit" title="Modifier" onclick="moudifierLivre(${livre.id_livre})">
            <i class="fa-solid fa-pen-to-square"></i> Modifier
          </button>
          <button type="button" class="btn-icon btn-delete" title="Supprimer" onclick="supprimerLivre(${livre.id_livre})">
            <i class="fa-solid fa-trash-can"></i> Supprimer
          </button>
        </td>
      `;

      table.appendChild(row);
    });
  } catch (error) {
    console.error(error);
    alert("Erreur lors du chargement");
  }
}

//! Create
async function ajouterLivre(livre) {
  const response = fetch(API_URL, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(livre),
  });

  const data = (await response).json();

  console.log(data);

  resetForm();
  afficheLivres();
}

//! Mise a jour Livre
async function miseAJourLivre(livre) {
  const response = fetch(API_URL, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(livre),
  });

  const data = (await response).json();
  console.log(data);

  resetForm();
  afficheLivres();
}

//! Get Livre By Id
async function getlivre(id) {
  const response = await fetch(API_URL);
  const livres = await response.json();

  const livre = livres.find((livre) => livre.id_livre == id);
  console.log(livre);
  return livre;
}

//! Moudifier
async function moudifierLivre(id) {
  const livre = await getlivre(id);

  if (!livre) {
    alert("Livre introuvable");
    return;
  }

  document.getElementById("id_livre").value = livre.id_livre;
  document.getElementById("titre").value = livre.titre;
  document.getElementById("auteur").value = livre.auteur;
  document.getElementById("id_categorie").value = livre.id_categorie;
  document.getElementById("isbn").value = livre.isbn;
  document.getElementById("annee_publication").value = livre.annee_publication;
  document.getElementById("quantite").value = livre.quantite;

  document.getElementById("formBtn").innerHTML = `
    <i class="fa-solid fa-plus"></i>
    <span>Moudifier Livre</span>
  `
}

//! Supprimer
async function supprimerLivre(id) {
  const response = fetch(API_URL, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({id_livre: id}),
  });

  const data = (await response).json();

  console.log(data);

  afficheLivres();
}


//! Rechercher
document
  .getElementById("searchInput")
  .addEventListener("input", function() {
    const query = document.getElementById("searchInput").value;

    if (query == "") {
      afficheLivres();
    }

    afficheLivres(query);
  })





//* Form
document
  .getElementById("formLivre")
  .addEventListener("submit", async function (event) {
    event.preventDefault();
    const id = document.getElementById("id_livre").value;
    const livre = {
      titre: document.getElementById("titre").value,
      auteur: document.getElementById("auteur").value,
      id_categorie: document.getElementById("id_categorie").value,
      isbn: document.getElementById("isbn").value,
      annee_publication: document.getElementById("annee_publication").value,
      quantite: document.getElementById("quantite").value,
    };

    //& Update
    if (id !== "") {
      livre.id_livre = id;
      console.log(id);
      await miseAJourLivre(livre);
    } else {
      await ajouterLivre(livre);
    }
  });

//?

//? Reset
function resetForm() {
  document.getElementById("titre").value = "";
  document.getElementById("auteur").value = "";
  document.getElementById("id_categorie").value = "";
  document.getElementById("isbn").value = "";
  document.getElementById("annee_publication").value = "";
  document.getElementById("quantite").value = "";

  document.getElementById("formBtn").innerHTML = `
    <i class="fa-solid fa-plus"></i>
    <span>Ajouter au catalogue</span>
  `
}

//? Initialisation

document.addEventListener("DOMContentLoaded", function () {
  afficheLivres();
});
