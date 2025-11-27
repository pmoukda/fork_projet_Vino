import api from "./axios";

export function getproduits(page, limit, filtre) {
  if (filtre?.type === "identite") {
    return api.get(`/produits/couleur/${filtre.value}`);
  }

  if (filtre?.type === "pays") {
    return api.get(`/produits/pays/${filtre.value}`);
  }

  return api.get("/produits", {
    params: { page, limit },
  });
}

export function getproduit(id) {
  return api.get(`/produits/${id}`);
}
